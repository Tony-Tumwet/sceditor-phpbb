<?php
/**
 *
 * @author    DSR!
 * @since     09.02.18
 * @version   2.0.0
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace dsr\sceditor\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event listener
 */
class listener implements EventSubscriberInterface
{
    /** @var \phpbb\template\template */
    private $template;
    /** @var \phpbb\user */
    private $user;
    /** @var \phpbb\config\config */
    private $config;
    /** @var \phpbb\db\driver\driver_interface */
    private $db;

    private $editor_buttons_icons;
    private $editor_normal_toolbar;
    private $editor_quick_toolbar;
    private $css_theme_name;
    private $css_fixes_name;
    private $sceditor_url;

    public function __construct(
        \phpbb\db\driver\driver_interface $db,
        \phpbb\template\template $template,
        \phpbb\config\config $config,
        \phpbb\user $user, $root_path
    ) {
        $this->template = $template;
        $this->user     = $user;
        $this->config   = $config;
        $this->db       = $db;

        // monocons / material
        $this->editor_buttons_icons = 'monocons';

        $this->editor_normal_toolbar = 'bold,italic,underline,strike,subscript,superscript|left,center,right,justify|' .
            'size,color,removeformat|cut,copy,pastetext|bulletlist,orderedlist,indent,outdent|table|' .
            'code,quote|horizontalrule,image,link,unlink|emoticon,youtube|custombbcodes,maximize,source';
        $this->editor_quick_toolbar  = 'bold,italic,underline|color,removeformat|quote|image,link,unlink|emoticon,youtube|maximize,source';

        // square / modern / office / default / defaultdark
        $this->css_theme_name       = 'square.min.css';

        $this->css_fixes_name       = 'editarea.css';
        $this->sceditor_url         = $root_path . 'ext/dsr/sceditor/styles/all/template';
    }

    static public function getSubscribedEvents()
    {
        return array(
            //'core.display_custom_bbcodes' => 'initialize_editor',
            'core.generate_smilies_after' => 'initialize_editor',
            'core.viewtopic_modify_page_title' => 'initialize_editor'
        );
    }

    public function initialize_editor()
    {
        $this->template->assign_vars(array(
            'S_SCEDITOR'             => true,
            'EDITOR_BUTTONS_ICONS'   => $this->editor_buttons_icons,
            'EDITOR_NORMAL_TOOLBAR'  => $this->editor_normal_toolbar,
            'EDITOR_QUICK_TOOLBAR'   => $this->editor_quick_toolbar,
            'MAX_FONTSIZE'           => $this->config['max_post_font_size'],
            'U_EMOTICONS_ROOT'       => $this->root_path . $this->config['smilies_path'] . '/',
            'U_CSS_THEME'            => $this->sceditor_url . '/js/themes/' . $this->css_theme_name,
            'U_CSS_FIXES'            => $this->sceditor_url . '/js/themes/' . $this->css_fixes_name,
            'U_TOOLS_IMG'            => $this->sceditor_url . '/assets'
        ));

        $lang = $this->_get_lang();
        if ($lang)
        {
            $this->template->assign_var('L_SCEDITOR_LANG', $lang);
        }

        $result = $this->_get_smileys();
        while ($row = $this->db->sql_fetchrow($result))
        {
            $this->template->assign_block_vars('emoticons', array('code' => $row['code'], 'url' => $row['smiley_url']));
        }
    }

    private function _get_lang()
    {
        $lang = substr($this->user->lang['USER_LANG'], 0, 2);
        if ('en' === $lang)
        {
            return false;
        }

        $languages_dir = realpath(__DIR__ . '/../styles/all/template/js/languages');

        return is_readable( "$languages_dir/{$lang}.js") ? $lang : false;
    }

    private function _get_smileys()
    {
        // We need to get all smilies with url and code
        $sql = 'SELECT smiley_url, code
			FROM ' . SMILIES_TABLE . '
			GROUP BY smiley_url, code';
        // Caching the smilies for 30 minutes should be okay
        $result = $this->db->sql_query($sql, 1800);
        //$this->db->sql_freeresult($result);

        return $result;
    }
}
