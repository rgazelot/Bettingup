<?php

namespace Bettingup\TicketBundle\Entity;

trait BetType
{
    public function getBetTypes()
    {
        return [
            "1 X 2:",
            "1 2 pari spécial (mise remboursée si le match se termine par une victoire à l'extérieur dans le temps réglementaire):",
            "1 2 pari spécial (mise remboursée si le match se termine par une victoire à domicile dans le temps réglementaire):",
            "1 2 spécial (mise remboursée si la rencontre se termine par un match nul dans le temps réglementaire):",
            "Ecart entre les équipes:",
            "Résultat à la mi-temps:",
            "Quelle équipe va gagner la 2ème mi-temps (seuls comptent les buts marqués lors de la 2ème mi-temps)?:",
            "Nombre de buts marqués en 2ème mi-temps:",
            "Nombre de buts marqués:",
            "Nombre de buts marqués par l'équipe 1:",
            "Nombre de buts marqués par l'équipe 1 au cours de la 2ème mi-temps:",
            "Nombre de buts marqués par l'équipe 1 au cours de la 1ère mi-temps:",
            "Nombre de buts marqués par l'équipe 2 au cours de la 2ème mi-temps:",
            "Nombre de buts marqués par l'équipe 2 au cours de la 1ère mi-temps:",
            "Nombre de buts marqués par l'équipe 2:",
            "Nombre de buts marqués en 1ère mi-temps:",
            "Chance double:",
            "Mi-temps - Chance double:",
            "Mi-temps ou fin du match (pari gagnant pour un résultat à la mi-temps ou à la fin du match):",
            "Mi-temps/Résultat final:",
            "Quelle équipe va remporter les deux mi-temps ?:",
            "Pari sur les buts, 1ère mi-temps:",
            "Pari sur les buts (temps réglementaire):",
            "Pari sur les buts (options supplémentaires):",
            "L'équipe 2 marquera-t-elle un but ?:",
            "Les deux équipes marqueront-elles lors de la rencontre ?:",
            "Les deux équipes marqueront-elles en 1ère mi-temps ?:",
            "Quelle équipe marquera le dernier but ?:",
            "Quelle équipe va marquer le premier but de la 1ère mi-temps ?:",
            "Quelle équipe va marquer le premier but?:",
            "L'équipe 1 marquera-t-elle lors de chaque mi-temps ?:",
            "L'équipe 2 marquera-t-elle lors de chaque mi-temps ?:",
            "L'équipe 1 marquera-t-elle un but ?:",
            "Quelle équipe va marquer le premier but de la 2ème mi-temps ?:",
            "Un buteur un but au cours du match (temps régl./but contre son camp exclu):",
            "Un joueur marque le 1er but:",
            "Un buteur marque 2 buts ou plus:",
            "Un buteur marque 3 buts ou plus:",
            "Le joueur marque et son équipe gagne:",
            "Le joueur sera le dernier buteur:",
            "Le nombre de buts marqués sera-t-il pair ou impair ? (Aucun but marqué compte comme pair):",
            "Le nombre total de buts marqués en 1ère mi-temps sera-t-il impair ou pair ? (0 buts compte comme pair):",
            "Nombre de buts marqués entre la minute 30m01s et la mi-temps:",
            "Nombre de buts marqués entre la mi-temps et la minute 60m00s:",
            "Nombre de buts marqués entre les minutes 60m01s et 75m00s:",
            "Nombre de buts marqués entre la minute 30m01s et la mi-temps:",
            "Nombre de buts marqués entre les minutes 0 et 15m00s:",
            "Nombre de buts marqués entre les minutes 0 et 15m00s:",
            "Nombre de buts marqués entre les minutes 15m01s et 30m00s:",
            "Nombre de buts marqués entre les minutes 15m01s et 30m00s:",
            "Nombre de buts marqués entre la minute 75m01s et la fin du match:",
            "Nombre de buts marqués entre la minute 75m01s et la fin du match:",
            "Nombre de buts marqués entre les minutes 60m01s et 75m00s:",
            "Nombre de buts marqués entre la mi-temps et la minute 60m00s:",
            "Y aura t-il plus de buts marqués en 1ère ou en 2ème mi-temps ?:",
            "Durant quelle mi-temps l'équipe 1 marquera-t-elle le plus de buts ?:",
            "Durant quelle mi-temps l'équipe 2 marquera-t-elle le plus de buts ?:",
            "L'équipe 1 gagnera-t-elle sans encaisser de but ?:",
            "L'équipe 2 gagnera-t-elle sans encaisser de buts ?:",
            "Une équipe gagnera-t-elle avec exactement deux buts de différence ?:",
            "Une équipe gagnera-t-elle avec exactement un but de différence ?:",
            "Une équipe gagnera-t-elle avec exactement trois buts de différence ?:",
        ];
    }

    public function getBetTypesChoices()
    {
        return [
            //0
            [
                '1',
                'N',
                '2',
            ],
            //1
            [
                '1',
                'N',
            ],
            //2
            [
                'N',
                '2',
            ],
            //3
            [
                '1',
                '2',
            ],
            //4
            [
                "L'équipe 1 gagne avec 3 buts d'avance ou plus",
                "L'équipe 1 gagne avec exactement 2 buts d'avance",
                "L'équipe 1 gagne avec 1 but d'avance, fait match nul ou perd",
                "L'équipe 2 gagne avec 1 but d'avance, fait match nul ou perd",
                "L'équipe 2 gagne avec exactement 2 buts d'avance",
                "L'équipe 2 gagne avec 3 buts d'avance ou plus",
                "L'équipe 1 Gagne ou fait match nul",
                "L'équipe 2 gagne avec exactement 1 but d'avance",
                "L'équipe 2 gagne avec 2 buts d'avance ou plus",
                "L'équipe 1 gagne avec 2 buts d'avance ou plus",
                "L'équipe 1 gagne avec exactement 1 but d'avance",
                "L'équipe 2 Gagne ou fait match nul",
            ],
            //5
            [
                '1',
                'N',
                '2',
            ],
            //6
            [
                '1',
                'N',
                '2',
            ],
            //7
            [
                "Aucun but",
                "1 but",
                "2 buts",
                "3 buts ou plus",
                "Plus de 0,5",
                "Moins de 0,5",
                "Plus de 1,5",
                "Moins de 1,5",
                "Plus de 2,5",
                "Moins de 2,5",
            ],
            //8
            [
                "Aucun but",
                "1 but",
                "2 buts",
                "3 buts",
                "4 buts",
                "5 buts ou plus",
                "Plus de 0,5",
                "Moins de 0,5",
                "Plus de 1,5",
                "Moins de 1,5",
                "Plus de 2,5",
                "Moins de 2,5",
                "Plus de 3,5",
                "Moins de 3,5",
                "Plus de 4,5",
                "Moins de 4,5",
            ],
            //9
            [
                "Aucun but",
                "1 but",
                "2 buts",
                "3 buts ou plus",
            ],
            //10
            [
                "Aucun but",
                "1 but",
                "2 buts",
                "3 buts ou plus",
            ],
            //11
            [
                "Aucun but",
                "1 but",
                "2 buts",
                "3 buts ou plus",
            ],
            //12
            [
                "Aucun but",
                "1 but",
                "2 buts",
                "3 buts ou plus",
            ],
            //13
            [
                "Aucun but",
                "1 but",
                "2 buts",
                "3 buts ou plus",
            ],
            //14
            [
                "Aucun but",
                "1 but",
                "2 buts",
                "3 buts ou plus",
            ],
            //15
            [
                "Aucun but",
                "1 but",
                "2 buts",
                "3 buts ou plus",
                "Plus de 0,5",
                "Moins de 0,5",
                "Plus de 1,5",
                "Moins de 1,5",
                "Plus de 2,5",
                "Moins de 2,5",
            ],
            //16
            [
                "L'équipe 1 ou X",
                "X ou L'équipe 12",
                "L'équipe 1 ou L'équipe 2",
            ],
            //17
            [
                "L'équipe 1 ou X",
                "X ou L'équipe 2",
                "L'équipe 1 ou L'équipe 2",
            ],
            //18
            [
                "L'équipe 1",
                "X",
                "L'équipe 2",
            ],
            //19
            [
                "L'équipe 1 / L'équipe 1",
                "X / L'équipe 1",
                "L'équipe 2 / L'équipe 1",
                "L'équipe 1 / X",
                "X / X",
                "L'équipe 2 / X",
                "L'équipe 1 / L'équipe 2",
                "X / L'équipe 2",
                "L'équipe 2 / L'équipe 2",
            ],
            //20
            [
                "L'équipe 1",
                "L'équipe 2",
                "Aucune équipe ne gagnera les deux mi-temps",
            ],
            //21
            [
                "0-0",
                "1-0",
                "1-1",
                "0-1",
                "2-0",
                "2-1",
                "2-2",
                "1-2",
                "0-2",
                "3-0",
                "3-1",
                "3-2",
                "3-3",
                "2-3",
                "1-3",
                "0-3",
                "4-0",
                "4-1",
                "4-2",
                "4-3",
                "4-4",
                "3-4",
                "2-4",
                "1-4",
                "0-4",
            ],
            //22
            [
                "0-0",
                "1-0",
                "1-1",
                "0-1",
                "2-0",
                "2-1",
                "2-2",
                "1-2",
                "0-2",
                "3-0",
                "3-1",
                "3-2",
                "3-3",
                "2-3",
                "1-3",
                "0-3",
                "4-0",
                "4-1",
                "4-2",
                "4-3",
                "4-4",
                "3-4",
                "2-4",
                "1-4",
                "0-4",
                "Un autre résultat",
            ],
            //23
            [
                "1:0, 2:0 ou 3:0",
                "0:1, 0:2 ou 0:3",
                "4:0, 5:0 ou 6:0",
                "0:4, 0:5 ou 0:6",
                "2:1, 3:1 ou 4:1",
                "1:2, 1:3 ou 1:4",
                "3:2, 4:2, 4:3 ou 5:1",
                "2:3, 2:4, 3:4 ou 1:5",
                "L'équipe 1 gagne avec n'importe quel autre résultat",
                "L'équipe 2 gagne avec n'importe quel autre résultat",
                "Égalité",
            ],
            //24
            [
                "Oui",
                "Non",
            ],
            //25
            [
                "Oui",
                "Non",
            ],
            //26
            [
                "Oui",
                "Non",
            ],
            //27
            [
                "L'équipe 1",
                "Aucun but",
                "L'équipe 2",
            ],
            //28
            [
                "L'équipe 1",
                "Aucun but",
                "L'équipe 2",
            ],
            //29
            [
                "L'équipe 1",
                "Aucun but",
                "L'équipe 2",
            ],
            //30
            [
                "Oui",
                "Non",
            ],
            //31
            [
                "Oui",
                "Non",
            ],
            //32
            [
                "Oui",
                "Non",
            ],
            //33
            [
                "L'équipe 1",
                "Aucun but",
                "L'équipe 2",
            ],
            //34
            [
                "Oui",
            ],
            //35
            [
                "Oui",
            ],
            //36
            [
                "Oui",
            ],
            //37
            [
                "Oui",
            ],
            //38
            [
                "Oui",
            ],
            //39
            [
                "Oui",
            ],
            //40
            [
                "Pair",
                "Impair",
            ],
            //41
            [
                "Pair",
                "Impair",
            ],
            //42
            [
                "Plus de 1,5",
                "Moins de 1,5",
            ],
            //43
            [
                "Plus de 0,5",
                "Moins de 0,5",
            ],
            //44
            [
                "Plus de 1,5",
                "Moins de 1,5",
            ],
            //45
            [
                "Plus de 0,5",
                "Moins de 0,5",
            ],
            //46
            [
                "Plus de 1,5",
                "Moins de 1,5",
            ],
            //47
            [
                "Plus de 0,5",
                "Moins de 0,5",
            ],
            //48
            [
                "Plus de 1,5",
                "Moins de 1,5",
            ],
            //49
            [
                "Plus de 0,5",
                "Moins de 0,5",
            ],
            //50
            [
                "Plus de 1,5",
                "Moins de 1,5",
            ],
            //51
            [
                "Plus de 0,5",
                "Moins de 0,5",
            ],
            //52
            [
                "Plus de 0,5",
                "Moins de 0,5",
            ],
            //53
            [
                "Plus de 1,5",
                "Moins de 1,5",
            ],
            //54
            [
                "1ère mi-temps",
                "Même nombre de buts",
                "2ème mi-temps",
            ],
            //55
            [
                "1ère mi-temps",
                "Même nombre de buts",
                "2ème mi-temps",
            ],
            //56
            [
                "1ère mi-temps",
                "Même nombre de buts",
                "2ème mi-temps",
            ],
            //57
            [
                "Oui",
                "Non",
            ],
            //58
            [
                "Oui",
                "Non",
            ],
            //59
            [
                "Oui",
                "Non",
            ],
            //60
            [
                "Oui",
                "Non",
            ],
            //61
            [
                "Oui",
                "Non",
            ],
        ];
    }
}
