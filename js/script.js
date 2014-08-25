(function($, Backbone, _, Marionette ) {
	var media = wp.media,
		cloudtree = window.cloudtree = new Backbone.Marionette.Application();
	cloudtree.View = {};
	cloudtree.View.Attachment = media.View.extend({
		tagName: 'tr',
		template:  media.template('media-item'),

		initialize: function() {
			var self = this;
			this.$el.draggable({
				cursor: 'move',
				cursorAt: { top: -12, left: -20 },
				start: function() {
					self.controller.trigger( 'selection:add', self.model );
				},
				helper: "clone"
			});
			if ( self.model.get('type' ) === 'media-folder' ) {
				this.$el.droppable({
					drop: function( event, ui ) {
						folder = self.model;
						self.controller.trigger( 'selection:moveToFolder', folder );
					}
				});
			}
		}
	});
	cloudtree.View.Attachments = media.View.extend({
		initialize: function(options) {
			this._viewsByCid = {};
			this.controller = options.controller;

			this.collection.on( 'add', function( attachment ) {
				this.views.add( this.createAttachmentView( attachment ), {
					at: this.collection.indexOf( attachment )
				});
			}, this );

			this.collection.on( 'remove', function( attachment ) {
				var view = this._viewsByCid[ attachment.cid ];
				delete this._viewsByCid[ attachment.cid ];

				if ( view ) {
					view.remove();
				}
			}, this );

			this.collection.on( 'reset', this.render, this );
			this.collection.fetch();

		},

		createAttachmentView: function( attachment ) {
			var view = new cloudtree.View.Attachment({
				controller: this.controller,
				model:      attachment,
				collection: this.collection
			});
			return this._viewsByCid[ attachment.cid ] = view;
		},

		/**
		 * @todo pretty sure we won't need this.
		 *
		 * @param  {[type]} domNode [description]
		 * @return {[type]}         [description]
		 */
		getAttachmentFromDomNode: function( domNode ) {
			var view = _.find( this.views.all(), function( view ) {
				return view.el.isSameNode( domNode );
			} );
			if ( view && view.model ) {
				return view.model;
			}
		}

	});

	wp.api.models.MediaFilesystemItem = wp.api.models.Post.extend({
		moveToFolder: function( folder ) {
			var meta = this.get( 'post_meta' ) || [],
				newMeta = [];
			if ( ! _.isArray( meta ) )
				meta = [];
			meta.forEach(function(element, index, array) {
				if ( element.key !== 'media_folder_parent' ) {
					newMeta.push( element );
				}
			});
			newMeta.push( { key: 'media_folder_parent', value: folder.get('ID') } );
			this.set( 'post_meta', newMeta );
			this.save();
		}
	});

	/**
	 * Backbone media library collection
	 */
	wp.api.collections.MediaFilesystemItems = wp.api.collections.MediaLibrary.extend({
		model: wp.api.models.MediaFilesystemItem,

		initialize: function( options ) {
			this.options = options || {};
			this.slug = this.options.slug || '';
			this.controller = this.options.controller || null;

			wp.api.collections.MediaLibrary.prototype.initialize.apply( this, arguments );
		},

		fetch: function( options ) {
			_.extend( { slug: this.slug }, options );
			wp.api.collections.MediaLibrary.prototype.fetch.apply( this, arguments );
		},

		url: function() {
			return WP_API_Settings.root + '/media-folder/' + this.slug;
		}
	});

	cloudtree.Router = Backbone.Router.extend({
		routes: {
			"*actions": "defaultRoute" // matches http://example.com/#anything-here
		}
	});

	cloudtree.FilesystemController = Backbone.Model.extend({
		initialize: function() {

			// @todo why don't models have an option for events hashes like views?
			this.on( 'selection:add', _.bind( this.addFileToSelection, this ) );
			this.on( 'selection:moveToFolder', _.bind( this.moveSelectionToFolder, this ) );

			this.Router = new cloudtree.Router;
			this.Router.on( 'route:defaultRoute', _.bind( this.default, this ) );
			this.selection = new wp.api.collections.MediaFilesystemItems();
		},

		default: function( slug ) {
			$('.allfiles tbody').html('');
			this.attachments = new cloudtree.View.Attachments({
				controller: this,
				collection: new wp.api.collections.MediaFilesystemItems({
					controller: this,
					slug: slug
				}),
				el: '.allfiles tbody'
			});
			this.attachments.render();
		},

		addFileToSelection: function( model ) {
			this.selection.add( [ model ] );
		},

		moveSelectionToFolder: function( folder ) {
			this.selection.invoke( 'moveToFolder', folder );
		}
	});

	cloudtree.addInitializer(function(options){
		new cloudtree.FilesystemController;

		Backbone.history.start();
	});

	cloudtree.start();


})(jQuery, Backbone, _, Marionette );