(function() {
    tinymce.create('tinymce.plugins.Wptuts', {
        /**
         * Initializes the plugin, this will be executed after the plugin has been created.
         * This call is done before the editor instance has finished it's initialization so use the onInit event
         * of the editor instance to intercept that event.
         *
         * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
         * @param {string} url Absolute URL to where the plugin is located.
         */
        init : function(ed, url) {
            ed.addButton('clear_before', {
                title : 'Clear Before',
                cmd : 'clearbefore',
                // image : url + '/clearbefore.png'
            });

            ed.addButton('clear_after', {
                title : 'Clear After',
                cmd : 'clearafter',
                // image : url + '/clearbefore.png'
            });

            ed.addCommand('clearbefore', function() {


                ed.execCommand('mceInsertContent', false,'<span class="marker">\ufeff</span>');


                // 记录最初的选择用来操作后恢复
                var rng = ed.selection.getRng(1);
                
                
                var rng2 = rng.cloneRange();
                rng2.setStartBefore(jQuery(ed.getBody()).find('p:first').get(0));
                rng2.setEndBefore(jQuery(ed.getBody()).find('span.marker').get(0));
                ed.selection.setRng(rng2);
                ed.selection.setContent('');


                jQuery(ed.getBody()).find('span.marker').remove();
                ed.selection.setRng(rng);



            });
            
            ed.addCommand('clearafter', function () {

                ed.execCommand('mceInsertContent', false,'<span class="marker">\ufeff</span>');


                // 记录最初的选择用来操作后恢复
                var rng = ed.selection.getRng(1);


                var rng2 = rng.cloneRange();
                rng2.setStartAfter(jQuery(ed.getBody()).find('span.marker').get(0));
                rng2.setEndAfter(jQuery(ed.getBody()).find('p:last').get(0));
                ed.selection.setRng(rng2);
                ed.selection.setContent('');

                jQuery(ed.getBody()).find('span.marker').remove();
                ed.selection.setRng(rng);

            })

        },

        /**
         * Creates control instances based in the incomming name. This method is normally not
         * needed since the addButton method of the tinymce.Editor class is a more easy way of adding buttons
         * but you sometimes need to create more complex controls like listboxes, split buttons etc then this
         * method can be used to create those.
         *
         * @param {String} n Name of the control to create.
         * @param {tinymce.ControlManager} cm Control manager to use inorder to create new control.
         * @return {tinymce.ui.Control} New control instance or null if no control was created.
         */
        createControl : function(n, cm) {
            return null;
        },

        /**
         * Returns information about the plugin as a name/value array.
         * The current keys are longname, author, authorurl, infourl and version.
         *
         * @return {Object} Name/value array containing information about the plugin.
         */
        getInfo : function() {
            return {
                longname : 'Wptuts Buttons',
                author : 'Lee',
                authorurl : 'http://wp.tutsplus.com/author/leepham',
                infourl : 'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/example',
                version : "0.1"
            };
        }
    });

    // Register plugin
    tinymce.PluginManager.add( 'wptuts', tinymce.plugins.Wptuts );
})();