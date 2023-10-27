<?php

namespace App\Http\Controllers;
use App\Models\BlogUcaldas;
use Illuminate\Http\Request;
use Goutte\Client;

class WebScrapingController extends Controller
{
//composer require weidner/goutte
//https://zubairidrisaweda.medium.com/introduction-to-web-scraping-with-laravel-a217e1444f7c
//https://www.ucaldas.edu.co/portal/universidad-de-caldas-renovo-convenio-interinstitucional-con-la-universidad-de-la-frontera-de-chile
    
    public function webScraping(string $url){

        $client = new Client();
        $website = $client->request('GET', $url);
        $blog = new BlogUcaldas();
        $blog->name = '';
        $blog->content = '';
        $website->filter('div > h2')->each(function ($node) use ($blog) {    
            $blog->name = $node->text(); 
            $blog->content .= $node->text(); 
        });
        $website->filter('article div p')->each(function ($node) use ($blog) {
            $blog->content .= $node->text();
        });
        $blog->save(); 
    }

    public function webScrapingAll(){
        $links = array('https://www.ucaldas.edu.co/portal/campus-de-la-universidad-de-caldas-seleccionados-para-los-comicios-electorales-del-29-de-octubre/', 'https://www.ucaldas.edu.co/portal/laboratorios-de-antropologia-biologia-y-arqueologia-de-la-universidad-caldas-cumplen-25-anos/', 'https://www.ucaldas.edu.co/portal/conmemoracion-50-anos-licenciatura-en-ciencias-sociales-dialogo-de-saberes-implicaciones-de-la-formacion-docente-en-ciencias-sociales/',  'https://www.ucaldas.edu.co/portal/hasta-el-martes-siete-', 'https://www.ucaldas.edu.co/portal/pronto-cierran-inscripciones-a-la-maestria-en-culturas-y-droga/', 'https://www.ucaldas.edu.co/portal/maestria-en-ciencias-veterinarias-de-la-universidad-de-caldas-recibe-acreditacion-de-alta-calidad/', 'https://www.ucaldas.edu.co/portal/una-semana-musical-gracias-a-la-temporada-de-conciertos-de-la-universidad-de-caldas/', 'https://www.ucaldas.edu.co/portal/especial-de-halloween-del-taller-permanente-de-apreciacion-cinematografica/');

        foreach( $links as $link){
            $this->webScraping($link);
        }
    }

}
