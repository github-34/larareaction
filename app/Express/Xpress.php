<?php

namespace App\Express;

class Xpress {

    // Expression Types
    const APPLAUSE = 1;
    const LIKEDISLIKE = 2;
    const HOTCOLD = 3;
    const HOTCOLDGRADIENT = 4;
    const COGNITIVE = 5;
    const EMOTIVE = 6;
    const FIVESTAR = 7;
    const MICHELINSTAR = 8;
    const VOTE = 9;
    const ELO = 10;

    // Applause
    const APPLAUDE = 1;

    // Like-Dislike
    const LIKE = 1;
    const DISLIKE = 1;

    // Hot-Cold
    const COLD = 1;
    const HOT = 2;

    // Hot-Cold Gradient
    const GRADIENTCOLD = 1;
    const GRADIENTHOT = 5;

    // Cognitive
    const INTERESTING = 1;
    const CLEAR = 2;
    const CONFUSING = 3;

    // Emotive
    const HAPPY = 1;
    const SUPERHAPPY = 2;
    const SAD = 3;

    // Five star
    const ONESTARS = 1;
    const TWOSTARS = 2;
    const THREESTARS = 3;
    const FOURSTARS = 4;
    const FIVESTARS = 5;

    // Michelin Star
    const ONEMICHELINSTARS = 1;
    const TWOMICHELINSTARS = 2;
    const THREEMICHELINSTARS = 3;

    // Vote
    const INFAVOR = 1;
    const AGAINST = 2;
    const ABSTAIN = 3;

    // ELO [Enter Exact Rating or By Class]
    const CLASSD = 1200;
    const CLASSC = 1400;
    const CLASSB = 1600;
    const CLASSA = 1800;
    const CANDIDATEMASTER = 2000;
    const MASTER = 2200;
    const INTERNATIONALMASTER = 2300;
    const GRANDMASTER = 2400;
    const SUPERGRANDMASTER = 2700;
}

?>
