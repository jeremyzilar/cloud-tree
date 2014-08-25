(function($, Backbone, _ ) {
	window.cloudtree = {
		View: {}
	}, media = wp.media;
	cloudtree.View.Attachment = media.View.extend({
		tagName: 'tr',
		template:  media.template('media-item'),

		initialize: function() {
			var self = this;
			this.$el.draggable({
				cursor: 'move',
				cursorAt: { top: -12, left: -20 },
				helper: function( event ) {
					return $( '<div class="ui-widget-header">I\'m a custom helper</div>' );
				},
				stop: function( event, ui ) {
					debugger;
				}
			});
			this.$el.droppable({
				drop: function( event, ui ) {
					var folder = cloudtree.attachments.getAttachmentFromDomNode( this );
					self.model.moveToFolder( folder );
				}
			});
		}
	});
	cloudtree.View.Attachments = media.View.extend({
		initialize: function() {

			this._viewsByCid = {};


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
				model:      attachment,
				collection: this.collection
			});
			return this._viewsByCid[ attachment.cid ] = view;
		},

		getAttachmentFromDomNode: function( domNode ) {
			var view = _.find( this.views.all(), function( view ) {
				return view.el.isSameNode( domNode );
			} );
			if ( view && view.model ) {
				return view.model;
			}
		}

	});

	wp.api.models.MediaFolder = wp.api.models.Media.extend({
		moveToFolder: function( folder ) {
			var meta = this.get( 'post_meta' ) || [];

			meta.push( { key: '_media_folder_parent', value: folder.get('id') } );
			this.set( 'post_meta', meta );
		}
	}); //MediaFolderModel
	/**
	 * Backbone media library collection
	 */
	wp.api.collections.MediaFolder = wp.api.collections.MediaLibrary.extend({
		model: wp.api.models.MediaFolder,

		initialize: function( options ) {
			this.options = options;
			this.slug = this.options.slug || '';
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

	var AppRouter = Backbone.Router.extend({
		routes: {
			"*actions": "defaultRoute" // matches http://example.com/#anything-here
		}
	});
	// Initiate the router
	var app_router = new AppRouter;

	app_router.on('route:defaultRoute', function( slug ) {
	$('.allfiles tbody').html('');
		cloudtree.attachments = new cloudtree.View.Attachments({
			collection: new wp.api.collections.MediaFolder({
				slug: slug
			}),
			el: '.allfiles tbody'
		});
		cloudtree.attachments.render();
	});

	// Start Backbone history a necessary step for bookmarkable URL's
	Backbone.history.start();

})(jQuery, Backbone, _ );