<div class="form-group">
	<label for="eyecatch_image" class="control-label">アイキャッチ画像</label>
	<input type="file" name="file" id="eyecatch_upload" /><br />
	<img id="eyecatch_url" src="" />
	<input type="hidden" id="eyecatch_image" name="eyecatch_image" value="" />
</div>

<!-- Include jquery.uplaod-1.0.2.min.js For Realtime Image Display -->
<script src="/js/jquery.upload-1.0.2.min.js"></script>
<script>
    // The event of uploading eyecatch image
    $(function() {
        $('#eyecatch_upload').change(function() {
            // The request link  
            var fn="/edit/save_image";
            fn=encodeURI(fn); // URL encoding
            // Asynchonous communication for image upload.
            $(this).upload(fn, function(res) {
                // Process when the image upload success
                $("#eyecatch_url").attr("src",'/img/thumb/'+ res); // Display the thumb image for preview
                $("#eyecatch_image").attr("value",res); // The image file name for saving to database 
            }, 'html');
        });
    });
</script>
