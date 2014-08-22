(function($, Backbone, _ ) {
	window.cloudtree = {
		View: {}
	}, media = wp.media;
	cloudtree.View.Attachment = media.View.extend({
		tagName: 'tr',
		template:  media.template('media-item')
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
				model:                attachment,
				collection:           this.collection
			});
			return this._viewsByCid[ attachment.cid ] = view;
		}

	});

	/**
	 * Backbone media library collection
	 */
	var FilesystemFolder = wp.api.collections.MediaLibrary.extend({
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
			return WP_API_Settings.root + '/filesystem-folder/' + this.slug;
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
        cloudtree.attachments = new cloudtree.View.Attachments({
			collection: new FilesystemFolder({
				slug: slug
			}),
			el: '.allfiles tbody'
		});
		cloudtree.attachments.render();
    });

    // Start Backbone history a necessary step for bookmarkable URL's
    Backbone.history.start();

})(jQuery, Backbone, _ );