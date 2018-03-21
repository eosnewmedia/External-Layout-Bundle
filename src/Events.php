<?php
namespace Enm\Bundle\ExternalLayoutBundle;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class Events
{
    const HTML_LOADED = 'enm.external_layout.html_loaded';
    
    const HTML_MANIPULATED = 'enm.external_layout.html_manipulated';

    const HTML_BEFORE_DUMP = 'enm.external_layout.html_before_dump';
}
