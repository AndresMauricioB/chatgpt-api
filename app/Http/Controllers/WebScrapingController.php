<?php

namespace App\Http\Controllers;

use App\Models\BlogUcaldas;
use Illuminate\Http\Request;
use Goutte\Client;



class WebScrapingController extends Controller
{
//composer require weidner/goutte
//https://zubairidrisaweda.medium.com/introduction-to-web-scraping-with-laravel-a217e1444f7c


    public function webScraping(){

        $client = new Client();
        $website = $client->request('GET', 'https://www.ucaldas.edu.co/portal/universidad-de-caldas-renovo-convenio-interinstitucional-con-la-universidad-de-la-frontera-de-chile');
       
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

}
