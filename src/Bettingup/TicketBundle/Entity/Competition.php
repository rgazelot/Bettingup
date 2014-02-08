<?php

namespace Bettingup\TicketBundle\Entity;

trait Competition
{
    public static function getCompetitions()
    {
        return [
            'Ligue 1',
            'Ligue 2',
            'National',
            'Coupe de France',
            'Champions League',
            'Europa League',
            'Euro 2016',
            'Premier League',
            'League Championship',
            'Capital One Cup',
            'Liga BBVA',
            'Liga Adelante',
            'Copa del Rey',
            'Serie A',
            'Serie B',
            'Coppa Italia',
            'Bundesliga',
            '2e Bundesliga',
            'Coupe d\'Allemagne',
            'Liga Zon Sagres',
            'Liga2 Cabovisao',
            'Eredivisie',
            'Jupiler Pro League',
            'Scottish Premiership',
            'Superleague',
        ];
    }
}
