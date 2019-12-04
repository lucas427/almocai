<?php

/**
 * gerarCartao : gera os cartões com checkboxes ou radios cujos itens
 * 
 * vêm de uma tabela específica do BD
 * @param string $nomeArquivoCartao nome do arquivo HTML do cartão 
 * @param string @nomeArquivoItem nome do arquivo HTML do item do cartão
 * @param array $registros os registros a ser colocados como itens
 * @param mixed $idItensSelecionados o(s) iten(s) a aparecerem selecionados (os que o usuário já marcou)
 * 
 * @return string o componente cartão a ser carregado em main.html
 */
function gerarCartao(string $nomeArquivoCartao, string $nomeArquivoItem, $registros, $idItensSelecionados)
{
    if (!is_array($idItensSelecionados))
        $idItensSelecionados = array($idItensSelecionados);
    // Isso é feito para que esse cartão possa ser universal, isto é, possível de ser usado tanto em cartões que usam radio (única opção) quanto em cartões que usam checkbox (múltiplas opções)

    $itens = '';
    foreach ($registros as $registro) {
        $item = file_get_contents($nomeArquivoItem);
        $item = str_replace('{codigo}', $registro->getCodigo(), $item);
        $item = str_replace('{descricao}', $registro->getDescricao(), $item);
        $checked = "";
        if (in_array($registro->getCodigo(), $idItensSelecionados))
            $checked = " checked ";
        $item = str_replace('{checked}', $checked, $item);
        $itens .= $item;
    }
    $cartao = file_get_contents($nomeArquivoCartao);
    $cartao = str_replace('{{itens}}', $itens, $cartao);
    return $cartao;
}