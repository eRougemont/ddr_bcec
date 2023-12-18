/**
 * Click in section, create a div with images
 * 
 */

class Bcec
{
    static lastImages;
    static lib = {
        "bcec195105": 20,
        "bcec195203": 28,
        "bcec195204": 24,
        "bcec195205": 24,
        "bcec195207": 24,
        "bcec195209": 32,
        "bcec195210": 28,
        "bcec195211": 40,
        "bcec195301": 28,
        "bcec195302": 14,
        "bcec195304": 16,
        "bcec195306": 20,
        "bcec195310": 32,
        "bcec195312": 24,
        "bcec195402": 20,
        "bcec195404": 24,
        "bcec195407": 28,
        "bcec195408": 28,
        "bcec195408b": 36,
        "bcec195411": 28,
        "bcec195501": 24,
        "bcec195503": 32,
        "bcec195509": 28,
        "bcec195510": 28,
        "bcec195512": 40,
        "bcec195602": 32,
        "bcec195605": 60,
        "bcec195605all": 64,
        "bcec195607": 28,
        "bcec195607__europe-russia": 40,
        "bcec195609": 36,
        "bcec195611": 68,
        "bcec195611all": 68,
        "bcec195702": 28,
        "bcec195704": 68,
        "bcec195705": 56,
        "bcec195710": 68,
        "bcec195712": 92,
        "bcec195802": 58,
        "bcec195805": 88,
        "bcec195807": 52,
        "bcec195809": 68,
        "bcec195811": 100,
        "bcec195811it": 96,
        "bcec195812": 52,
        "bcec195900": 82,
        "bcec195903": 48,
        "bcec195905": 102,
        "bcec195909": 68,
        "bcec195909de": 68,
        "bcec195912": 124,
        "bcec196000de": 64,
        "bcec196010": 60,
        "bcec196100a": 52,
        "bcec196100b": 92,
        "bcec196100c": 76,
        "bcec196100d": 100,
        "bcec196100e": 56,
        "bcec196100een": 56,
        "bcec196100f": 100,
        "bcec196204": 164,
        "bcec196208": 96,
        "bcec196302": 92,
        "bcec196305": 100,
        "bcec196307": 140,
        "bcec196403": 112,
        "bcec196409": 100,
        "bcec196412": 120,
        "bcec196500a": 112,
        "bcec196500b": 172,
        "bcec196600a": 140,
        "bcec196600b": 196,
        "bcec196700a": 76,
        "bcec196700b": 84,
        "bcec196800": 208,
        "bcec196900": 128,
        "bcec197000a": 204,
        "bcec197000b": 164,
        "bcec197100": 56,
        "bcec197200": 96,
        "bcec197400a": 87,
        "bcec197400b": 68,
        "bcec197500a": 72,
        "bcec197500b": 252,
        "bcec197500c": 60,
        "bcec197500d": 44,
        "bcec197600": 148,
        "bcec197700": 56,
    }
    static book()
    {
        const docid = "TODO";
        const screen = document.getElementById('mainframe');
        const imagesId = docid + '_images';
        let images = document.getElementById(imagesId);
        if (!images) {
            // build it.
            let images = document.createElement('div');
            images.setAttribute('id', imagesId);
            const img1 = null;
            for (let i = 1; i < Bcec.lib[docid]; i++) {
                const img = document.createElement('img');
                img.setAttribute('src', imagesId);
                if (i == 1) img1 = im 
            }
        }
        if (Bcec.lastImages) {
            Bcec.lastImages.style.display = 'none';
            lastImages = images;
        }
    }
}