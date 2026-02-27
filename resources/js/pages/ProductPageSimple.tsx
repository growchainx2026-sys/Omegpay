import { useEffect } from 'react';
import { Box, HStack, Image, Link, Text, VStack } from '@chakra-ui/react';
import { useConfig } from '../stores/config';
import { Box as ChakraBox, Grid, GridItem } from '@chakra-ui/react';
import { ProductCheckoutForm } from '../components/ProductCheckoutForm';
import { Checkout } from '@/types/checkout';
import { Produto } from '@/types/produto';
import { Setting } from '@/types/setting';
import { Helper } from '@/helpers/helpers';
import { Head, usePage } from '@inertiajs/react';
import { DepoimentoComponent } from '@/components/renderable/DepoimentoComponent';

export interface ProductPageProps {
  checkout: Checkout
  produto?: Produto
  setting: Setting
  vendedor: string
  utmfy: string | null
}

// Declare para o TS saber que fbq existe
declare global {
  interface Window {
    fbq: (...args: any[]) => void;
  }
}

export default function ProductPageSimple({ setting, produto, checkout, vendedor, utmfy }: ProductPageProps) {

  // Debug: Log props no console
  useEffect(() => {
    console.log('=== ProductPageSimple Debug ===');
    console.log('Setting:', setting);
    console.log('Produto:', produto);
    console.log('Checkout:', checkout);
    console.log('Vendedor:', vendedor);
    console.log('Utmfy:', utmfy);
    console.log('=============================');
  }, []);

  const { url } = usePage(); // retorna a URL completa
  const params = new URLSearchParams(url.split('?')[1]);
  const ref = params.get('ref');

  const { setAffiliateRef } = useConfig();

  // E no useEffect, capturar quando estiver pronto
  useEffect(() => {
    setAffiliateRef(ref);
    const onReady = () => {
      if (window.fbq) {
        setFbq(window.fbq);
      }
    };
    window.addEventListener('fbqReady', onReady);
    return () => window.removeEventListener('fbqReady', onReady);
  }, []);

  const { 
    setVendedor, 
    setSetting, 
    setCheckout, 
    setProduto, 
    setDepoimentos, 
    depoimentos, 
    setMetaAds, 
    meta_ads, 
    setFbq, 
    setUtmfy 
  } = useConfig();

  const registerVisit = () => {
    fetch('/api/checkout/visit/' + checkout?.uuid, {
      method: 'POST',
    })
    .then(response => {
      console.log('Visit registered:', response.ok);
    })
    .catch(error => {
      console.error('Error registering visit:', error);
    });
  }

  useEffect(() => {
    console.log('Setting initial state...');
    console.log('Setting:', setting);
    
    setSetting(setting);
    setCheckout(checkout);
    setProduto(produto);
    setVendedor(vendedor);
    setDepoimentos(checkout?.depoimentos || []);
    setMetaAds(produto?.meta_ads as any);
    setUtmfy(utmfy);
    registerVisit();
  }, []);

  // Verificação de segurança
  if (!checkout || !produto) {
    return (
      <Box 
        minH="100vh" 
        w="100vw" 
        display="flex" 
        alignItems="center" 
        justifyContent="center"
        bg="gray.900"
      >
        <VStack spacing={4}>
          <Text color="white" fontSize="xl">Carregando produto...</Text>
          <Text color="gray.400" fontSize="sm">Se esta mensagem persistir, verifique o console</Text>
        </VStack>
      </Box>
    );
  }

  return (
    <>
      <Head title={produto?.name || 'Produto'}>
        <meta name="title" content={produto?.name || 'Produto'} />
        <meta name="description" content={produto?.description || 'Descrição do produto'} />

        <meta property="og:type" content="website" />
        <meta property="og:url" content={window.location.href} />
        <meta property="og:title" content={produto?.name || 'Produto'} />
        <meta property="og:description" content={produto?.description || 'Descrição do produto'} />
        <meta property="og:image" content={Helper.storageUrl(produto?.image) || '/product-placeholder.svg'} />

        <meta property="twitter:card" content="summary_large_image" />
        <meta property="twitter:url" content={window.location.href} />
        <meta property="twitter:title" content={produto?.name || 'Produto'} />
        <meta property="twitter:description" content={produto?.description || 'Descrição do produto'} />

        {meta_ads && (
          <script
            dangerouslySetInnerHTML={{
              __html: `
              !function(f,b,e,v,n,t,s) {
                if (f.fbq) return;
                n = f.fbq = function() {
                  n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
                };
                if (!f._fbq) f._fbq = n;
                n.push = n;
                n.loaded = !0;
                n.version = '2.0';
                n.queue = [];
                t = b.createElement(e);
                t.async = !0;
                t.src = v;
                s = b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t, s)
              }(window, document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');
              fbq('init', '${meta_ads}');
              fbq('track', 'PageView');
                window.dispatchEvent(new Event('fbqReady'));
            `,
            }}
          />
        )}

        {produto?.google_ads && (
          <script
            dangerouslySetInnerHTML={{
              __html: `
              <!-- Google Tag Manager -->
              <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
              new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
              j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
              'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
              })(window,document,'script','dataLayer','${produto?.google_ads}');</script>
              <!-- End Google Tag Manager -->
              `
            }}
          />
        )}
      </Head>
      {produto?.google_ads && (
        <script
          dangerouslySetInnerHTML={{
            __html: `
              <!-- Google Tag Manager (noscript) -->
              <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=${produto?.google_ads}"
              height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
              <!-- End Google Tag Manager (noscript) -->
              `
          }}
        />
      )}

      <Box
        minH="100vh"
        w={'100vw'}
        overflowX={'hidden'}
        overflowY={'auto'}
        bg={checkout?.template?.bg || '#1a202c'}
        py={{ base: 0, xl: 8 }}
        m={0}
        display={{ base: 'flex', xl: 'flex' }}
        justifyContent={'center'}
      >
        <Grid
          mt={0}
          pt={0}
          templateColumns="repeat(12, 1fr)"
          gap={8}
          borderRadius="lg"
          w={{ base: '96%', xl: '70vw' }}
          p={{ base: 2, xl: 8 }}
          px={{ base: -2, xl: 8 }}
          overflowX={'hidden'}
        >
          {/* Removido o GridItem do ProductMain - versão simplificada */}

          <GridItem
            overflowX={'hidden'} 
            colSpan={{ base: 12, xl: 12 }} 
            p={{ base: 0, xl: 2 }} 
            py={0} 
            mx={{ base: 2, xl: 0 }} 
            ml={{ base: '-3px', xl: 0 }}
          >
            <ChakraBox 
              p={{ base: 0, xl: 6 }} 
              borderRadius="lg" 
              boxShadow="sm" 
              bg={checkout?.template?.bg_form_payment || 'white'}
            >
              <ProductCheckoutForm />
            </ChakraBox>
          </GridItem>
          
        </Grid>
      </Box>
    </>
  );
}