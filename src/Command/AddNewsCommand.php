<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Console\Input\InputArgument;
use GuzzleHttp\Client;


class AddNewsCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:add-news';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $client = HttpClient::create();
        //simulate getting data
        $response = $client->request('GET','http://parser-nginx:80/news');
        $news_data = $response->getContent();
        var_dump(json_decode($news_data));
        //create data to be posted
        $encoded_data = json_decode($news_data);
        //queue data to message bus
        $data = $client->request('POST','http://parser-nginx:80/schedule',['json'=>[
            'title'=>$encoded_data->title,
            'description'=>$encoded_data->description,
            'image'=>$encoded_data->image
        ]]);

        $output->writeln(strval($news_data));
        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        // the command help shown when running the command with the "--help" option
        $this->setHelp("This command allows you to add news after simulation scrapping");
        
    }
}





?>