<div id="upload" class="row">
	<!-- The file upload form used as target for the file upload widget -->
  <form id="fileupload" action="<?php echo $uri; ?>/" method="POST" enctype="multipart/form-data">

  	<!-- The fileinput-button span is used to style the file input field as button -->
    <span class="fileinput-button">
      <input type="file" name="files[]" multiple>
    </span>
    <div id="dropzone">
    	<i class="fa fa-cloud-upload"></i>
    </div>
    <table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>
    <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
    <div class="row fileupload-buttonbar">
      <div class="col-lg-7">
        <button type="submit" class="btn btn-xs btn-primary start">
          <i class="glyphicon glyphicon-upload"></i>
          <span>Upload</span>
        </button>
        <button type="reset" class="btn btn-xs btn-warning cancel">
          <i class="glyphicon glyphicon-ban-circle"></i>
          <span>Cancel</span>
        </button>
        <button type="button" class="btn btn-xs btn-danger delete">
          <i class="glyphicon glyphicon-trash"></i>
          <span>Delete</span>
        </button>
        <input type="checkbox" class="toggle">
        <!-- The global file processing state -->
        <span class="fileupload-process"></span>
      </div>
      <!-- The global progress state -->
      <div class="col-lg-5 fileupload-progress fade hidden">
        <!-- The global progress bar -->
        <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
          <div class="progress-bar progress-bar-success" style="width:0%;"></div>
        </div>
        <!-- The extended global progress state -->
        <div class="progress-extended">&nbsp;</div>
      </div>
    </div>
    <!-- The table listing the files available for upload/download -->
    <div class="pre_files hidden">
    	<div class="pre_file">
    		<div class="pre_thumb">
    			<img src="http://placehold.it/80x80"/>
    		</div>
    		<div class="pre_filename">
    			kepler.png
    		</div>
    		<div class="pre_filesize">
    			1.97 MB
    		</div>
    		<div class="pre_filename">
    			<button class="btn btn-xs btn-primary start">
            <i class="glyphicon glyphicon-upload"></i>
            <span>Start</span>
          </button>
          <button class="btn btn-xs btn-warning cancel">
            <i class="glyphicon glyphicon-ban-circle"></i>
            <span>Cancel</span>
          </button>
    		</div>
    		<div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>

    	</div>
    </div>
  </form>
</div>