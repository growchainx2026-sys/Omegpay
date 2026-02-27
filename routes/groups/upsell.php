<?php 

use App\Models\Efi;
use App\Models\Setting;
use Illuminate\Support\Facades\Route;


Route::get('/upsell/upsellminjs', function () {
    $appUrl = config('app.url');

    $adquirencia = Setting::first()->adquirencia_card;
    $identificador_conta = '123456';
    if ($adquirencia == 'efi') {
        $identificador_conta = Efi::first()->identificador_conta;
    }

    $script = <<<'JS'
function _0x38a7(_0x256953,_0x3749e5){const _0x2d687e=_0x3048();return _0x38a7=function(_0x3c9f3e,_0x27e637){_0x3c9f3e=_0x3c9f3e-(0x9*-0x42b+0x15*0x127+0xdb5*0x1);let _0x3f7678=_0x2d687e[_0x3c9f3e];return _0x3f7678;},_0x38a7(_0x256953,_0x3749e5);}(function(_0x4077d6,_0x13bd89){const _0x3df5f0=_0x38a7,_0x3bfa0d=_0x4077d6();while(!![]){try{const _0x48440d=-parseInt(_0x3df5f0(0x7b))/(0x7a*-0x4+0x147*-0x2+0x3*0x17d)+-parseInt(_0x3df5f0(0xaf))/(0x1*-0xd5a+0x164c+-0x8f0)*(-parseInt(_0x3df5f0(0xbb))/(-0x2009+0x2ab*0xb+0x2b3))+-parseInt(_0x3df5f0(0xa0))/(0x2*0xb23+0x13c5+-0x1d*0x173)+-parseInt(_0x3df5f0(0xf2))/(-0x1*-0x267a+0x25e7+-0x4c5c)*(-parseInt(_0x3df5f0(0xee))/(-0x49*-0x55+-0x1745+-0xf2))+parseInt(_0x3df5f0(0x108))/(0x10ee+-0x1d*0x17+-0xf4*0xf)+parseInt(_0x3df5f0(0x76))/(0x18ab*-0x1+0x16fc+0x1b7)*(parseInt(_0x3df5f0(0xe9))/(0x2265*-0x1+0x1bd2+0x24*0x2f))+-parseInt(_0x3df5f0(0x103))/(-0x1ee7+0x216d+0xd4*-0x3)*(parseInt(_0x3df5f0(0xf8))/(0x3ba*0x3+-0x1b00+0x83*0x1f));if(_0x48440d===_0x13bd89)break;else _0x3bfa0d['push'](_0x3bfa0d['shift']());}catch(_0x2b59df){_0x3bfa0d['push'](_0x3bfa0d['shift']());}}}(_0x3048,-0x2831e+-0x13*-0x13a9+0x5001*0x9),(async function(){const _0x5e1845=_0x38a7,_0xdf5c92={'QTegw':_0x5e1845(0xe8)+_0x5e1845(0xb1)+'a}','ICYjA':_0x5e1845(0xb3),'jAwZU':function(_0x41188b,_0x25191a){return _0x41188b+_0x25191a;},'WpdKJ':_0x5e1845(0x110),'FPdmH':function(_0x55ec91,_0x2945ef){return _0x55ec91(_0x2945ef);},'Lhnqr':_0x5e1845(0x69),'zmKwI':_0x5e1845(0xdf),'QilWU':function(_0x3796a4){return _0x3796a4();},'vTfVM':function(_0x42595a){return _0x42595a();},'dSqQZ':_0x5e1845(0xfb),'bCwCC':_0x5e1845(0x8d),'MTLaF':_0x5e1845(0xbd),'yIOVN':_0x5e1845(0x10d),'CvMHz':_0x5e1845(0x122),'OMjEN':_0x5e1845(0xbf),'WSXuX':_0x5e1845(0x77),'DMiSz':_0x5e1845(0xc9),'Ojpxj':_0x5e1845(0xc3),'UzlEy':_0x5e1845(0xc7)+_0x5e1845(0x124)+_0x5e1845(0x100)+_0x5e1845(0xa5)+_0x5e1845(0x114),'GZBQH':function(_0x51c735,_0x5cea2d,_0x168961){return _0x51c735(_0x5cea2d,_0x168961);},'oXkCU':_0x5e1845(0xf5)+'0','rxOif':_0x5e1845(0xd3)+_0x5e1845(0xda),'TWLZJ':_0x5e1845(0xff)+_0x5e1845(0x70)+_0x5e1845(0x86),'PpNbF':function(_0x4c22c6,_0x58139a){return _0x4c22c6===_0x58139a;},'WOhZg':_0x5e1845(0x107),'kzVMh':_0x5e1845(0x104),'TfAKn':_0x5e1845(0x129)+_0x5e1845(0x99),'vhuCw':_0x5e1845(0x10f)+_0x5e1845(0xc4)+_0x5e1845(0x96),'jZCNq':_0x5e1845(0xa6),'ielUr':_0x5e1845(0xde)+_0x5e1845(0x10e),'ALzhX':_0x5e1845(0x83)+_0x5e1845(0x101),'aVXRt':function(_0x5d10e8,_0x18b6ea){return _0x5d10e8(_0x18b6ea);},'eCbGc':_0x5e1845(0xd9)+_0x5e1845(0xf0)+_0x5e1845(0x65)+_0x5e1845(0x68),'thCTk':_0x5e1845(0xcd),'CYKXe':_0x5e1845(0x89)+_0x5e1845(0x6c),'Akmgf':_0x5e1845(0xdd)+_0x5e1845(0x102),'GxRDp':_0x5e1845(0xdc)+_0x5e1845(0xd5),'pZUWA':_0x5e1845(0xef)+_0x5e1845(0x121),'exIYO':function(_0x2a0742,_0x43531f,_0x3033b4){return _0x2a0742(_0x43531f,_0x3033b4);},'zcrXw':_0x5e1845(0xe5),'pAuZf':_0x5e1845(0xdb)+_0x5e1845(0xf6)+_0x5e1845(0x85)+_0x5e1845(0x74)+_0x5e1845(0xa1)+_0x5e1845(0x8a)+_0x5e1845(0xfd)+_0x5e1845(0xb5)+_0x5e1845(0x127),'nqJdQ':_0x5e1845(0xba),'BzOhe':_0x5e1845(0xcb),'QhIRc':_0x5e1845(0x81)+_0x5e1845(0xd1)};let _0x26a6bd=document[_0x5e1845(0x11d)+_0x5e1845(0xbe)](_0xdf5c92[_0x5e1845(0xd4)]),_0x56ddc9=await _0xdf5c92[_0x5e1845(0xa9)](fetch,_0xdf5c92[_0x5e1845(0x98)])[_0x5e1845(0xe0)](_0x153f57=>_0x153f57[_0x5e1845(0x8f)]())[_0x5e1845(0xe0)](_0x4d29f3=>_0x4d29f3['ip'])[_0x5e1845(0xac)](_0xced0bc=>console[_0x5e1845(0x10c)](_0xced0bc));if(_0x26a6bd){let _0xff32cd=new URLSearchParams(window[_0x5e1845(0xb7)][_0x5e1845(0xa4)]),_0x95c13c=_0xff32cd[_0x5e1845(0xa3)](_0xdf5c92[_0x5e1845(0x79)]),_0x2b047e=document[_0x5e1845(0x11d)+_0x5e1845(0xbe)](_0xdf5c92[_0x5e1845(0xd0)]),_0x176558=document[_0x5e1845(0x11d)+_0x5e1845(0xbe)](_0xdf5c92[_0x5e1845(0x9e)]),_0x4c89fc=_0x26a6bd[_0x5e1845(0x95)+'te'](_0xdf5c92[_0x5e1845(0x72)]),_0x1cb800=_0x26a6bd[_0x5e1845(0x95)+'te'](_0xdf5c92[_0x5e1845(0x106)]),_0x225859=_0x26a6bd[_0x5e1845(0x7f)+'t'],_0x55465f=_0x176558[_0x5e1845(0x7f)+'t'],_0x5ebc09=_0x176558[_0x5e1845(0x95)+'te'](_0xdf5c92[_0x5e1845(0x88)]),_0xdf3e64,_0x1b59c4=await _0xdf5c92[_0x5e1845(0x12d)](fetch,_0x5e1845(0x10f)+_0x5e1845(0xc4)+_0x5e1845(0x73)+'t',{'method':_0xdf5c92[_0x5e1845(0x87)],'body':JSON[_0x5e1845(0xd6)]({'produto':_0x4c89fc,'order':_0x95c13c}),'headers':{'Content-Type':_0xdf5c92[_0x5e1845(0xd7)]}})[_0x5e1845(0xe0)](_0x480ecf=>_0x480ecf[_0x5e1845(0x8f)]())[_0x5e1845(0xe0)](_0x430a0e=>_0x430a0e)[_0x5e1845(0xac)](_0x3f2e56=>console[_0x5e1845(0x10c)](_0x3f2e56));if(_0xdf5c92[_0x5e1845(0x9f)](_0x1b59c4?.[_0x5e1845(0xea)],_0xdf5c92[_0x5e1845(0xf3)])){var _0x33728e=document[_0x5e1845(0xfe)+_0x5e1845(0xab)](_0xdf5c92[_0x5e1845(0x93)]);_0x33728e[_0x5e1845(0xd2)]=_0xdf5c92[_0x5e1845(0x11a)],document[_0x5e1845(0xe3)+_0x5e1845(0x11b)](_0xdf5c92[_0x5e1845(0x84)])[0x1*-0x92e+-0x1761+0x1*0x208f][_0x5e1845(0x109)+'d'](_0x33728e),_0x33728e[_0x5e1845(0xe7)]=function(){const _0x4b133f=_0x5e1845,_0x19573a={'djekx':_0xdf5c92[_0x4b133f(0x6a)],'tSOio':_0xdf5c92[_0x4b133f(0xec)],'ZcePf':function(_0xb53a12,_0x42c958){const _0x43afc1=_0x4b133f;return _0xdf5c92[_0x43afc1(0xb2)](_0xb53a12,_0x42c958);},'eydxQ':_0xdf5c92[_0x4b133f(0x82)]};let _0xbcacb=JSON[_0x4b133f(0x118)](_0xdf5c92[_0x4b133f(0x125)](atob,_0x1b59c4[_0x4b133f(0xcd)])),_0x4cc579=_0xdf5c92[_0x4b133f(0xbc)];console[_0x4b133f(0xb8)](_0xdf5c92[_0x4b133f(0xb0)],_0xbcacb),console[_0x4b133f(0xb8)](window[_0x4b133f(0x9a)]);async function _0x5b9c3e(){const _0x3845ae=_0x4b133f;try{const _0x27d9ab=await window[_0x3845ae(0x9a)][_0x3845ae(0xa8)][_0x3845ae(0xfc)+_0x3845ae(0x12c)](_0xbcacb?.[_0x3845ae(0x113)]?.[_0x3845ae(0x123)]('\x20',''))[_0x3845ae(0xc1)+_0x3845ae(0x94)]();_0x4cc579=_0x27d9ab;}catch(_0x24073a){}}async function _0x78dc4f(){const _0x442305=_0x4b133f;try{const _0x2b19a5=await EfiPay[_0x442305(0xa8)][_0x442305(0xb4)](_0x19573a[_0x442305(0xf4)])[_0x442305(0xed)+_0x442305(0xa2)](_0x19573a[_0x442305(0xe4)])[_0x442305(0x105)+_0x442305(0xc0)]({'brand':_0x4cc579,'number':_0xbcacb?.[_0x442305(0x113)]?.[_0x442305(0x123)]('\x20',''),'cvv':_0xbcacb?.[_0x442305(0xb6)],'expirationMonth':_0xbcacb?.[_0x442305(0xe6)][_0x442305(0xf7)]('/')[-0x83c+0x173*-0x1+0x9af],'expirationYear':_0x19573a[_0x442305(0x71)]('20',_0xbcacb?.[_0x442305(0xe6)][_0x442305(0xf7)]('/')[-0x70d+-0x78f+0xe9d]),'holderName':_0xbcacb?.[_0x442305(0xf9)],'holderDocument':_0x1b59c4?.[_0x442305(0xe2)],'reuse':![]})[_0x442305(0x8b)+_0x442305(0xca)]();_0xdf3e64=_0xdf3e64;}catch(_0x367111){console[_0x442305(0xb8)](_0x19573a[_0x442305(0xaa)],_0x367111[_0x442305(0x7c)+_0x442305(0x126)]);}}_0xdf5c92[_0x4b133f(0x119)](_0x5b9c3e),_0xdf5c92[_0x4b133f(0x91)](_0x78dc4f);};}_0x26a6bd[_0x5e1845(0xf1)+_0x5e1845(0xd8)](_0xdf5c92[_0x5e1845(0x8c)],function(){const _0x5815c5=_0x5e1845,_0x2806b6={'IKrrw':_0xdf5c92[_0x5815c5(0x66)],'WCejQ':_0xdf5c92[_0x5815c5(0x88)],'kQjnL':_0xdf5c92[_0x5815c5(0x7d)],'qjMkE':_0xdf5c92[_0x5815c5(0xfa)],'KJEuS':_0xdf5c92[_0x5815c5(0xae)],'ypkSz':_0xdf5c92[_0x5815c5(0xeb)],'yXzFx':_0xdf5c92[_0x5815c5(0x6d)],'qlVMr':_0xdf5c92[_0x5815c5(0x6e)],'sUhkA':_0xdf5c92[_0x5815c5(0x112)],'oKUjh':_0xdf5c92[_0x5815c5(0x6b)],'QthNN':function(_0x30b6b8,_0x1d0071,_0xad11c1){const _0x5f4ab7=_0x5815c5;return _0xdf5c92[_0x5f4ab7(0x75)](_0x30b6b8,_0x1d0071,_0xad11c1);},'oVaIi':_0xdf5c92[_0x5815c5(0xad)]};_0x26a6bd[_0x5815c5(0x97)+'te'](_0xdf5c92[_0x5815c5(0x66)],''),_0x176558[_0x5815c5(0x97)+'te'](_0xdf5c92[_0x5815c5(0x66)],''),_0x26a6bd[_0x5815c5(0x90)]=_0xdf5c92[_0x5815c5(0x9d)],_0x176558[_0x5815c5(0x97)+'te'](_0xdf5c92[_0x5815c5(0x88)],'#'),_0x176558[_0x5815c5(0x90)]=_0xdf5c92[_0x5815c5(0x7a)];let _0x263b6c={'order':_0x95c13c,'produto':_0x4c89fc,'ip':_0x56ddc9};if(_0xdf5c92[_0x5815c5(0x9f)](_0x1b59c4[_0x5815c5(0xea)],_0xdf5c92[_0x5815c5(0xf3)])){let _0x5a1c93=JSON[_0x5815c5(0x118)](_0xdf5c92[_0x5815c5(0x125)](atob,_0x1b59c4[_0x5815c5(0xcd)]));_0x263b6c[_0xdf5c92[_0x5815c5(0xce)]]=_0x5a1c93,_0x263b6c[_0xdf5c92[_0x5815c5(0xce)]][_0xdf5c92[_0x5815c5(0xe1)]]=_0xdf3e64;}_0xdf5c92[_0x5815c5(0x75)](fetch,_0xdf5c92[_0x5815c5(0xc5)],{'method':_0xdf5c92[_0x5815c5(0x87)],'body':JSON[_0x5815c5(0xd6)](_0x263b6c),'headers':{'Content-Type':_0xdf5c92[_0x5815c5(0xd7)]}})[_0x5815c5(0xe0)](_0x24ac1e=>_0x24ac1e[_0x5815c5(0x8f)]())[_0x5815c5(0xe0)](_0x2e9f9d=>{const _0xb53398=_0x5815c5;console[_0xb53398(0xb8)](_0x2e9f9d),_0x26a6bd[_0xb53398(0x90)]=_0x225859,_0x26a6bd[_0xb53398(0xb9)+_0xb53398(0x12b)](_0x2806b6[_0xb53398(0x116)]),_0x176558[_0xb53398(0xb9)+_0xb53398(0x12b)](_0x2806b6[_0xb53398(0x116)]),_0x176558[_0xb53398(0x90)]=_0x55465f,_0x176558[_0xb53398(0x97)+'te'](_0x2806b6[_0xb53398(0x120)],_0x5ebc09);if(_0x2e9f9d[_0xb53398(0x9c)])window[_0xb53398(0xb7)][_0xb53398(0x8d)]=_0x1cb800;else{let _0x2388b3=document[_0xb53398(0xfe)+_0xb53398(0xab)](_0x2806b6[_0xb53398(0xa7)]);_0x2388b3[_0xb53398(0x7e)][_0xb53398(0xcf)+_0xb53398(0x67)]=_0x2806b6[_0xb53398(0xc6)],_0x2388b3[_0xb53398(0x7e)][_0xb53398(0x6f)+'us']=_0x2806b6[_0xb53398(0x117)],_0x2388b3[_0xb53398(0x7e)][_0xb53398(0x11f)]=_0x2806b6[_0xb53398(0x9b)],_0x2388b3[_0xb53398(0x7e)][_0xb53398(0x11c)+'t']=_0x2806b6[_0xb53398(0x117)],_0x2388b3[_0xb53398(0x7e)][_0xb53398(0xc2)+'ht']=_0x2806b6[_0xb53398(0x117)],_0x2388b3[_0xb53398(0x7e)][_0xb53398(0x92)]=_0x2806b6[_0xb53398(0x8e)],_0x2388b3[_0xb53398(0x7e)][_0xb53398(0xcc)]=_0x2806b6[_0xb53398(0x10b)],_0x2388b3[_0xb53398(0x7e)][_0xb53398(0x12a)]=_0x2806b6[_0xb53398(0x80)],_0x2388b3[_0xb53398(0x7e)][_0xb53398(0xc8)+'t']=_0x2806b6[_0xb53398(0x80)],_0x2388b3[_0xb53398(0x90)]=_0x2e9f9d[_0xb53398(0x78)]??_0x2806b6[_0xb53398(0x11e)],_0x2b047e[_0xb53398(0x109)+'d'](_0x2388b3),_0x2806b6[_0xb53398(0x115)](setTimeout,()=>{const _0x1fb6ee=_0xb53398;_0x2b047e[_0x1fb6ee(0x10a)+'d'](_0x2388b3);},-0x25b7+0x227e+-0x19*-0xe9);}})[_0x5815c5(0xac)](_0x1dc748=>{const _0x467243=_0x5815c5,_0x3543c1=_0x2806b6[_0x467243(0x128)][_0x467243(0xf7)]('|');let _0xdaa643=0x2*0xd00+0x2183*-0x1+0x1*0x783;while(!![]){switch(_0x3543c1[_0xdaa643++]){case'0':_0x176558[_0x467243(0x97)+'te'](_0x2806b6[_0x467243(0x120)],_0x5ebc09);continue;case'1':_0x176558[_0x467243(0x90)]=_0x55465f;continue;case'2':console[_0x467243(0x10c)](_0x1dc748);continue;case'3':_0x26a6bd[_0x467243(0xb9)+_0x467243(0x12b)](_0x2806b6[_0x467243(0x116)]);continue;case'4':_0x176558[_0x467243(0xb9)+_0x467243(0x12b)](_0x2806b6[_0x467243(0x116)]);continue;case'5':_0x26a6bd[_0x467243(0x90)]=_0x225859;continue;}break;}});});}else console[_0x5e1845(0x10c)](_0xdf5c92[_0x5e1845(0x111)]);}()));function _0x3048(){const _0x207692=['KJEuS','parse','QilWU','pAuZf','sByTagName','paddingLef','querySelec','oKUjh','padding','WCejQ','ect-to','4px','replace','ocessar\x20o\x20','FPdmH','ription','md.min.js','oVaIi','payment_to','marginLeft','ibute','ber','exIYO','g?format=j','dSqQZ','Color','son','visa','QTegw','UzlEy','eview','WSXuX','DMiSz','borderRadi','processame','ZcePf','GxRDp','/ad/defaul','ipay/js-pa','GZBQH','105272tIDyFz','white','message','thCTk','TWLZJ','91892AnBKYp','error_desc','MTLaF','style','textConten','sUhkA','Botão\x20não\x20','WpdKJ','#preview-a','nqJdQ','.net/gh/ef','nto...','jZCNq','bCwCC','#upsell-pr','n-efi/dist','getPayment','BzOhe','href','yXzFx','json','innerText','vTfVM','color','zcrXw','Brand','getAttribu','/upsell','setAttribu','eCbGc','ken','EfiPay','ypkSz','status','rxOif','Akmgf','PpNbF','320336ELAGNB','yment-toke','ment','get','search','\x20Tente\x20nov','POST','kQjnL','CreditCard','aVXRt','eydxQ','ent','catch','oXkCU','CvMHz','32vhWsEs','zmKwI','cador_cont','jAwZU','production','setAccount','oken-efi-u','cvv','location','log','removeAttr','head','31659BXyZUM','Lhnqr','div','tor','8px','ardData','verifyCard','paddingRig','30px','api/pedido','vhuCw','qjMkE','Erro\x20ao\x20pr','marginRigh','10px','Token','click','marginTop','order','kzVMh','background','CYKXe','encontrado','src','Processand','ALzhX','to-id','stringify','ielUr','stener','https://ap','o...','https://cd','data-produ','#preview-r','applicatio','pag\x20=>\x20','then','TfAKn','document','getElement','tSOio','script','validade','onload','{$identifi','126ASJZvX','adquirente','OMjEN','ICYjA','setEnviron','5298BBVavG','data-redir','i.ipify.or','addEventLi','420gRHxKW','WOhZg','djekx','2|5|3|4|1|','n.jsdelivr','split','3188482kxDqjd','nomeCartao','yIOVN','disabled','setCardNum','/payment-t','createElem','Aguarde\x20o\x20','pagamento.','ccept','ecuse','10sgURBu','pagamento','setCreditC','pZUWA','efi','1049370CWzcuj','appendChil','removeChil','qlVMr','error','orange','n/json','{$appUrl}/','Mensagem:\x20','QhIRc','Ojpxj','numero','amente!','QthNN','IKrrw'];_0x3048=function(){return _0x207692;};return _0x3048();}
JS;


    $script = str_replace(['{$appUrl}', '{$identificador_conta}'], [$appUrl, $identificador_conta], $script);

    return response($script)
        ->header('Content-Type', 'application/javascript')
        ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
        ->header('Pragma', 'no-cache')
        ->header('Expires', '0');
});


/* BASE UPSELL DECRIPT */

/* 
(async function () {
    let button = document.querySelector('#preview-accept');
    let ip = await fetch('https://api.ipify.org?format=json')
        .then(res => res.json())
        .then(data => data.ip)
        .catch(err => console.error(err));

    if (button) {
        let params = new URLSearchParams(window.location.search);
        let order = params.get('order');
        let containerUpsell = document.querySelector('#upsell-preview');
        let recuse = document.querySelector('#preview-recuse');
        let produto = button.getAttribute('data-produto-id');
        let redirectTo = button.getAttribute('data-redirect-to');
        let textButton = button.textContent;
        let textRecuse = recuse.textContent;
        let urlRecuse = recuse.getAttribute('href');
        let payment_token;
        
        let adquirente = await fetch(`{$appUrl}/api/pedido/ad/default`, {
                method: 'POST',
                body: JSON.stringify({ produto, order }),
                headers: { 'Content-Type': 'application/json' }})
        .then(res => res.json())
        .then(data => data)
        .catch(err => console.error(err));
        if(adquirente?.adquirente === 'efi'){
            var sc = document.createElement('script');
            sc.src = "https://cdn.jsdelivr.net/gh/efipay/js-payment-token-efi/dist/payment-token-efi-umd.min.js";

            document.getElementsByTagName('head')[0].appendChild(sc);

            sc.onload = function () {
                let pag = JSON.parse(atob(adquirente.order));
                let bandeira = 'visa';
                console.log('pag => ', pag)
                // A lib já está no escopo global
                console.log(window.EfiPay); // ou só EfiPay

                async function identifyBrand() {
                    try {
                    const brand = await window.EfiPay.CreditCard
                        .setCardNumber(pag?.numero?.replace(' ', ''))
                        .verifyCardBrand();
                    bandeira = brand;
                    } catch (error) {
                    }
                }

                async function generatePaymentToken() {
                    try {
                    const result = await EfiPay.CreditCard
                        .setAccount("{$identificador_conta}")
                        .setEnvironment("production") // 'production' or 'sandbox'
                        .setCreditCardData({
                        brand: bandeira,
                        number: pag?.numero?.replace(' ', ''),
                        cvv: pag?.cvv,
                        expirationMonth: pag?.validade.split('/')[0],
                        expirationYear: "20"+pag?.validade.split('/')[1],
                        holderName: pag?.nomeCartao,
                        holderDocument: adquirente?.document,
                        reuse: false,
                        })
                        .getPaymentToken();

                    payment_token = payment_token;
                    } catch (error) {
                    console.log("Mensagem: ", error.error_description);
                    }
                }

                identifyBrand();
                generatePaymentToken();
                
            };    
        } 
        

        button.addEventListener('click', function () {
            button.setAttribute('disabled', '');
            recuse.setAttribute('disabled', '');
            button.innerText = 'Processando...';
            recuse.setAttribute('href', '#');
            recuse.innerText = 'Aguarde o processamento...';

            let data = { order, produto, ip };
            if(adquirente.adquirente === 'efi'){
                 let pag = JSON.parse(atob(adquirente.order));
                data['pagamento'] = pag;
                data['pagamento']['payment_token'] = payment_token;
            }

            fetch('{$appUrl}/api/pedido/upsell', {
                method: 'POST',
                body: JSON.stringify(data),
                headers: { 'Content-Type': 'application/json' }
            })
            .then(res => res.json())
            .then(res => {
                console.log(res);
                button.innerText = textButton;
                button.removeAttribute('disabled');
                recuse.removeAttribute('disabled');
                recuse.innerText = textRecuse;
                recuse.setAttribute('href', urlRecuse);
                if(res.status) {
                    window.location.href = redirectTo;
                } else {
                    let message = document.createElement('div');
                    message.style.backgroundColor = 'orange';
                    message.style.borderRadius = '4px';
                    message.style.padding = '8px';
                    message.style.paddingLeft = '4px';
                    message.style.paddingRight = '4px';
                    message.style.color = 'white';
                    message.style.marginTop = '10px';
                    message.style.marginLeft = '30px';
                    message.style.marginRight = '30px';
                    message.innerText = res.message ?? 'Erro ao processar o pagamento. Tente novamente!';
                    containerUpsell.appendChild(message);

                    setTimeout(() => {
                        containerUpsell.removeChild(message);  
                    }, 5000);
                }
            })
            .catch(err => {
                console.error(err);
                button.innerText = textButton;
                button.removeAttribute('disabled');
                recuse.removeAttribute('disabled');
                recuse.innerText = textRecuse;
                recuse.setAttribute('href', urlRecuse);
            });
        });
    } else {
        console.error('Botão não encontrado');
    }
})();
 */

