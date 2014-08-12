$(function(){
	
	// Override full _handleFileTreeEntry to enable empty folders as valid entries (in success handler)
	$.blueimp.fileupload.prototype._handleFileTreeEntry = function (entry, path) {
	    var that = this,
	        dfd = $.Deferred(),
	        errorHandler = function (e) {
	            if (e && !e.entry) {
	                e.entry = entry;
	            }
	            // Since $.when returns immediately if one
	            // Deferred is rejected, we use resolve instead.
	            // This allows valid files and invalid items
	            // to be returned together in one set:
	            dfd.resolve([e]);
	        },
	        successHandler = function (entries) {
	            that._handleFileTreeEntries(
	                entries,
	                path + entry.name + '/'
	            ).done(function (files) {
	            	/*
					Added files.push(entry) to force creation of empty folders
	            	*/
	            	files.push(entry);
	                dfd.resolve(files);
	            }).fail(errorHandler);
	        },
	        readEntries = function () {
	            dirReader.readEntries(function (results) {
	                if (!results.length) {
	                    successHandler(entries);
	                } else {
	                    entries = entries.concat(results);
	                    readEntries();
	                }
	            }, errorHandler);
	        },
	        dirReader, entries = [];
	    
	    path = path || '';

	    if (entry.isFile) {
	        if (entry._file) {
	            // Workaround for Chrome bug #149735
	            entry._file.relativePath = path;
	            dfd.resolve(entry._file);
	        } else {
	            entry.file(function (file) {
	                file.relativePath = path;
	                dfd.resolve(file);
	            }, errorHandler);
	        }
	    } else if (entry.isDirectory) {
	        dirReader = entry.createReader();
	        readEntries();
	    } else {
	        // Return an empy list for file system items
	        // other than files or directories:
	        dfd.resolve([]);
	    }
	    return dfd.promise();
	}
});