<script id="template-upload" type="text/x-tmpl">
    {% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
    <td>
    <span class="preview">
    {% if (!i && !o.options.autoUpload) { %}
    <button class="start" disabled>Start</button>
    {% } %}
    {% if (!i) { %}
    <button class="cancel"><i class="glyphicon glyphicon-ban-circle"></i></button>
    {% } %}
    </span>    
    </td>
    </tr>
    {% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
    {% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade" id="{%=btoa(file.name)%}">
    <td>
    <span class="preview">
        <span class="img_view">
    {% if (file.url) { %}
    <img src="{%=file.url%}">
    {% } %}
    </span>    
    <button class="delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}><i class="glyphicon glyphicon-ban-circle"></i></button>
    <input type="checkbox" name="delete" value="1" class="toggle styled-checkbox-1">
    </span>    
    </td>
    </tr>
    {% } %}
</script>