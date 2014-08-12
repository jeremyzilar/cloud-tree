$(function(){

    var ul = $('#upload ul');

    $('.fileupload').click(function(){
        // Simulate a click on the file input button
        // to show the file browser dialog
        $('.fileinput-button').find('input').click();
    });

    // - - - - - - - - - - - - - 

    $(document).bind('dragover', function (e) {

      var dropZone = $('body'),
        timeout = window.dropZoneTimeout;
      if (!timeout) {
        dropZone.addClass('in');
        $( "#dropzone" ).animate({
          opacity: '1',
          height: '100px'
        }, 'fast', function() {
          // Animation complete.
        });
        
      } else {
        clearTimeout(timeout);
      }
      var found = false,
        node = e.target;
      do {
        if (node === dropZone[0]) {
          found = true;
          break;
        }
        node = node.parentNode;
      } while (node != null);
      if (found) {
        dropZone.addClass('hover');
      } else {
        dropZone.removeClass('hover');
      }
      window.dropZoneTimeout = setTimeout(function () {
        window.dropZoneTimeout = null;
        dropZone.removeClass('in hover');
        $( "#dropzone" ).animate({
          opacity: '0',
          height: '0'
        }, 'fast', function() {
          // Animation complete.
        });
        // $(".fileupload-buttonbar").hide("fast");
        // $(".fileupload-buttonbar").show("fast");
      }, 100);
    });


    // - - - - - - - - - - - - - 

    // Initialize the jQuery File Upload plugin
    $('body').fileupload({

        url: handler,
        // This element will accept file drag/drop uploading
        dropZone: $('#dropzone'),

        drop: function(e, data) {
          console.log(e, data)
        },
        // This function is called when a file is added to the queue;
        // either via the browse button, or via drag/drop:
        add: function (e, data) {

            var tpl = $('<li class="working"><input type="text" value="0" data-width="48" data-height="48"'+
                ' data-fgColor="#0788a5" data-readOnly="1" data-bgColor="#3e4043" /><p></p><span></span></li>');

            // Append the file name and file size
            tpl.find('p').text(data.files[0].name)
                         .append('<i>' + formatFileSize(data.files[0].size) + '</i>');

            // Add the HTML to the UL element
            data.context = tpl.appendTo(ul);
            $(".fileupload-buttonbar").show();

            // Initialize the knob plugin
            tpl.find('input').knob();

            // Listen for clicks on the cancel icon
            tpl.find('span').click(function(){

                if(tpl.hasClass('working')){
                    jqXHR.abort();
                }

                tpl.fadeOut(function(){
                    tpl.remove();
                });

            });

            // Automatically upload the file once it is added to the queue
            var jqXHR = data.submit();
        },

        progress: function(e, data){

            // Calculate the completion percentage of the upload
            var progress = parseInt(data.loaded / data.total * 100, 10);

            // Update the hidden input field and trigger a change
            // so that the jQuery knob plugin knows to update the dial
            data.context.find('input').val(progress).change();

            if(progress == 100){
                data.context.removeClass('working');
            }
        },

        done: function(e, data) {
          console.log("DONE", e, data);
        },

        fail:function(e, data){
            // Something has gone wrong!
            data.context.addClass('error');
        }

    });

    // Prevent the default action when a file is dropped on the window
    $(document).on('drop dragover', function (e) {
        e.preventDefault();
    });

    // Helper function that formats the file sizes
    function formatFileSize(bytes) {
      if (typeof bytes !== 'number') {
        return '';
      }
      if (bytes >= 1000000000) {
        return (bytes / 1000000000).toFixed(2) + ' GB';
      }
      if (bytes >= 1000000) {
        return (bytes / 1000000).toFixed(2) + ' MB';
      }
      return (bytes / 1000).toFixed(2) + ' KB';
    }

    $('#fileupload').on('fileuploaddone', function (e, data) {
      console.log('done', e, data);
      // $("table tbody.files").empty();
    }).on("fileuploadfail", function(e, data){
      console.log('fail', e, data);
    }).on("fileuploadsubmit", function(e, data){
      //console.log('submit', e, data);
      $.each(data.files, function(index, file){
        data.formData = data.formData || [];
        var relativePath = file.isDirectory ? file.fullPath : file.relativePath;
        data.formData.push({ name: 'relative_path', value: relativePath});
        data.formData.push({ name: 'custom_path', value: custom_path});
        data.formData.push({ name: 'local_path', value: local_path});
      });
    });

    console.log($.blueimp.fileupload.prototype.options);
});