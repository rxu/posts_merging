<?php
/**
*
* Posts Merging extension for the phpBB Forum Software package.
* Brazilian Portuguese translation by vinny and update by eunaumtenhoid (c) 2017 [ver 2.1.0] (https://github.com/phpBBTraducoes)
* @copyright (c) 2013 phpBB Limited <https://www.phpbb.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine

$lang = array_merge($lang, array(
	'ACP_POSTS_MERGING'				=> 'Mesclar postagens',
	'ACP_POSTS_MERGING_EXPLAIN'		=> 'Aqui você pode aplicar as configurações da extensão Posts merging.',
	'ACP_POSTS_MERGING_SEPARATOR_PREVIEW'	=> 'Prever separador',
	'MERGE_INTERVAL'				=> 'Intervalo',
	'MERGE_INTERVAL_EXPLAIN'		=> 'Se um usuário enviar mais que 2 posts neste período de tempo, os posts serão mesclados num único post. Informação sobre horário, que será baseado de acordo a com último post enviado, será adicionada para cada post. Deixe em branco ou defina 0 para desativar este recurso.',
	'MERGE_NO_TOPICS'				=> 'Deletar estes tópicos',
	'MERGE_NO_TOPICS_EXPLAIN'		=> 'Lista de ID dos tópicos, separado por vírgula, onde o recurso não será aplicado. Essa opção será aplicada somente se o recurso de mesclar postagens estiver ativado.',
	'MERGE_NO_FORUMS'				=> 'Deletar estes fóruns',
	'MERGE_NO_FORUMS_EXPLAIN'		=> 'Este recurso <strong>será desativado nos fóruns selecionados</strong>. Selecione nenhum para usar este recurso em todos os fóruns.<br />Selecione ou desmarque múltiplos fóruns pressionando <samp>CTRL</samp> e clicando.',
	'MERGE_SEPARATOR'				=> 'Separador',
	'MERGE_SEPARATOR_EXPLAIN'		=> 'Aqui você pode configurar o separador que será exibido entre os posts mesclados.<br />Você poderá usar BBCodes de acordo com as configurações do fórum ou configuração de mensagens.<br /><br />Você também pode usar qualquer string de idioma dos arquivos do pacote de idioma: {L_<em>&lt;STRINGNAME&gt;</em>} onde <em>&lt;STRINGNAME&gt;</em> é o nome da string traduzida que você quer adicionar. Por exemplo, {L_WROTE} será exibido como “escreveu”.<br /><br />Use o marcador <em>&#37;s</em> (apenas uma vez) para incluir o horário entre o separador de posts mesclados.',
));
