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

			this.collection.more();

		},

		createAttachmentView: function( attachment ) {
			var view = new cloudtree.View.Attachment({
				model:                attachment,
				collection:           this.collection
			});
			return this._viewsByCid[ attachment.cid ] = view;
		}

	});

	// cloud.tree.AttachmentsAndFolders = wp.media.Attachments.extend({

	// });
	// ( null, {
	// 		props: _.extend( _.defaults( props || {}, { orderby: 'date' } ), { query: true } )
	// 	});

	cloudtree.attachments = new cloudtree.View.Attachments({
		collection: new media.model.Query( null, { args: media.model.Query.defaultArgs } ),
		el: '.allfiles tbody'
	});
	cloudtree.attachments.render();
	// media.view.Attachments = media.View.extend({

})(jQuery, Backbone, _ );