<?php

class Cache {

    public $expire;

    /**
     *
     * @param int $expire_in_seconds
     */
    public function __construct($expire_in_seconds = 3600) {
        $this->expire = $expire_in_seconds;
    }

    /**
     *
     * @param string $php_file
     * @param bool $return
     * @return string|boolean
     */
    public function load($php_file, $return = false) {
        // Configurações
        $cached = "../_cache/" . substr($php_file, 0, -4) . ".html";

        // Verifica se o arquivo cache existe e se ainda é válido
        if (file_exists($cached) && (filemtime($cached) > time() - $this->expire)) {
            // Lê o arquivo cacheado
            $conteudo = file_get_contents($cached);
        } else {
            // Acessa a versão dinâmica
            ob_start();
            require $php_file;
            $conteudo = ob_get_clean();

            // $conteudo = preg_replace('/\s*$^\s*/m', "", $conteudo);
            // $conteudo = preg_replace('/[ \t]+/', ' ', $conteudo);
           // $conteudo = preg_replace('/\n/m', "", $conteudo);
            // Cria o cache
            file_put_contents($cached, $conteudo);
        }

        // Exibe o conteúdo da página
        if ($return) {
            return $conteudo;
        } else {
            echo $conteudo;
            return false;
        }
    }

}
