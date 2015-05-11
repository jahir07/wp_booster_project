// JavaScript Document



( function() {
    tinymce.PluginManager.add( 'td_shortcode_plugin', function( editor, url ) {
        editor.addButton( 'td_button_key', {
            type: 'listbox',
            text: 'Shortcodes',
            classes: 'td_tinymce_shortcode_dropdown widget btn td-tinymce-dropdown',
            icon: false,
            onselect: function(e) {
            },
            values: [

                {text: 'Video Playlists', classes: 'td_tinymce_dropdown_title'},
                {text: 'Youtube playlist', onclick : function() {
                    tinymce.activeEditor.execCommand('mceInsertContent', false, '[td_block_video_youtube playlist_title="" playlist_yt="" playlist_auto_play="0"]' + tinyMCE.activeEditor.selection.getContent());
                }},
                {text: 'Vimeo playlist', onclick : function() {
                    tinymce.activeEditor.execCommand('mceInsertContent', false, '[td_block_video_vimeo playlist_title="" playlist_v="" playlist_auto_play="0"]' + tinyMCE.activeEditor.selection.getContent());
                }},


                {text: 'Smart lists', classes: 'td_tinymce_dropdown_title'},
                {text: 'Smart list end', onclick : function() {
                    tinymce.activeEditor.execCommand('mceInsertContent', false, '[td_smart_list_end]' + tinyMCE.activeEditor.selection.getContent());
                }},


                {text: 'Dropcaps', classes: 'td_tinymce_dropdown_title'},
                {text: 'Box', onclick : function() {
                    tinymce.activeEditor.execCommand('mceInsertContent', false, '[dropcap]' + tinyMCE.activeEditor.selection.getContent() + '[/dropcap]');
                }},
                {text: 'Circle', onclick : function() {
                    tinymce.activeEditor.execCommand('mceInsertContent', false, '[dropcap type="1"]' + tinyMCE.activeEditor.selection.getContent() + '[/dropcap]');
                }},
                {text: 'Regular', onclick : function() {
                    tinymce.activeEditor.execCommand('mceInsertContent', false, '[dropcap type="2"]' + tinyMCE.activeEditor.selection.getContent() + '[/dropcap]');
                }},
                {text: 'Bold', onclick : function() {
                    tinymce.activeEditor.execCommand('mceInsertContent', false, '[dropcap type="3"]' + tinyMCE.activeEditor.selection.getContent() + '[/dropcap]');
                }},


                {text: 'Button', classes: 'td_tinymce_dropdown_title'},
                {text: 'Default', onclick : function() {
                    tinymce.activeEditor.execCommand('mceInsertContent', false, '[button color="" size="" type="" target="" link=""]' + tinyMCE.activeEditor.selection.getContent() + '[/button]');
                }},
                {text: 'Square', onclick : function() {
                    tinymce.activeEditor.execCommand('mceInsertContent', false, '[button color="" size="" type="square" target="" link=""]' + tinyMCE.activeEditor.selection.getContent() + '[/button]');
                }},
                {text: 'Round', onclick : function() {
                    tinymce.activeEditor.execCommand('mceInsertContent', false, '[button color="" size="" type="round" target="" link=""]' + tinyMCE.activeEditor.selection.getContent() + '[/button]');
                }},
                {text: 'Outlined', onclick : function() {
                    tinymce.activeEditor.execCommand('mceInsertContent', false, '[button color="" size="" type="outlined" target="" link=""]' + tinyMCE.activeEditor.selection.getContent() + '[/button]');
                }},
                {text: '3d', onclick : function() {
                    tinymce.activeEditor.execCommand('mceInsertContent', false, '[button color="" size="" type="3d" target="" link=""]' + tinyMCE.activeEditor.selection.getContent() + '[/button]');
                }},
                {text: 'Square outlined', onclick : function() {
                    tinymce.activeEditor.execCommand('mceInsertContent', false, '[button color="" size="" type="square_outlined" target="" link=""]' + tinyMCE.activeEditor.selection.getContent() + '[/button]');
                }},

            ]

        });

    } );

} )();


