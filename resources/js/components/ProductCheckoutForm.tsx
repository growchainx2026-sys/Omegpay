import { useEffect, useState } from 'react';
import {
  VStack,
  Button,
  useToast,
  Divider,
  Text,
  HStack,
  Icon,
  Modal,
  ModalBody,
  ModalCloseButton,
  ModalContent,
  ModalFooter,
  ModalHeader,
  ModalOverlay,
  useDisclosure,
  Center,
  Image,
  Input,
  ButtonGroup,
} from '@chakra-ui/react';
import { ShoppingCart, CreditCard, LucideCheckCircle2, LucideCopy, LucideDownload } from 'lucide-react';
import { Formik, Form, useFormikContext, FormikHelpers } from 'formik';
import * as Yup from 'yup';
import { BuyerForm } from './BuyerForm';
import { ProductPaymentForm } from './ProductPaymentForm';
import { OrderSummary } from './OrderSummary';
import { useConfig } from '../stores/config';
import { QRCode } from 'react-qrcode-logo';
import { Helper } from '@/helpers/helpers';
import EfiPay from 'payment-token-efi';
import { CheckoutProvider } from '@stripe/react-stripe-js/checkout';
import { loadStripe } from '@stripe/stripe-js';
import { StripeWrapper } from './customs/StripeWrapper';
import { StripeForm } from './customs/StripeForm';

interface BuyerFormValues {
  name: string;
  email: string;
  cpf: string;
  phone: string;
}

interface PaymentFormValues {
  metodo: 'pix' | 'boleto' | 'cartao';
  desconto?: number;
  numero?: string;
  validade?: string;
  cvv?: string;
  parcelas?: string;
  nomeCartao?: string;
  installments?: number;
  payment_token?: string;
  card_mask?: string;
}

interface CheckoutFormValues {
  buyer: BuyerFormValues;
  payment: PaymentFormValues;
}

const validationSchema = Yup.object({
  buyer: Yup.object({
    name: Yup.string()
      .min(2, 'Nome deve ter pelo menos 2 caracteres')
      .required('Nome é obrigatório'),
    email: Yup.string()
      .email('Email inválido')
      .required('Email é obrigatório'),
    cpf: Yup.string()
      .matches(/^\d{3}\.\d{3}\.\d{3}-\d{2}$/, 'CPF inválido')
      .required('CPF é obrigatório'),
    phone: Yup.string()
      .matches(/^\(\d{2}\) \d{4,5}-\d{4}$/, 'Telefone inválido')
      .required('Telefone é obrigatório'),
  }),
  payment: Yup.object().shape({
    metodo: Yup.string().required('Selecione um método de pagamento'),
    numero: Yup.string().when('metodo', {
      is: 'cartao',
      then: (schema) => schema
        .matches(/^\d{4} \d{4} \d{4} \d{4}$/, 'Número do cartão inválido')
        .required('Número do cartão é obrigatório'),
      otherwise: (schema) => schema.notRequired(),
    }),
    validade: Yup.string().when('metodo', {
      is: 'cartao',
      then: (schema) => schema
        .matches(/^\d{2}\/\d{2}$/, 'Validade inválida (MM/AA)')
        .required('Validade é obrigatória'),
      otherwise: (schema) => schema.notRequired(),
    }),
    cvv: Yup.string().when('metodo', {
      is: 'cartao',
      then: (schema) => schema
        .matches(/^\d{3,4}$/, 'CVV inválido')
        .required('CVV é obrigatório'),
      otherwise: (schema) => schema.notRequired(),
    }),
    nomeCartao: Yup.string().when('metodo', {
      is: 'cartao',
      then: (schema) => schema
        .min(2, 'Nome deve ter pelo menos 2 caracteres')
        .required('Nome no cartão é obrigatório'),
      otherwise: (schema) => schema.notRequired(),
    }),
    parcelas: Yup.string().when('metodo', {
      is: 'cartao',
      then: (schema) => schema.required('Selecione o número de parcelas'),
      otherwise: (schema) => schema.notRequired(),
    }),
  }),
});

const validationSchemaStripe = Yup.object({
  buyer: Yup.object({
    name: Yup.string()
      .min(2, 'Nome deve ter pelo menos 2 caracteres')
      .required('Nome é obrigatório'),
    email: Yup.string()
      .email('Email inválido')
      .required('Email é obrigatório'),
    cpf: Yup.string()
      .matches(/^\d{3}\.\d{3}\.\d{3}-\d{2}$/, 'CPF inválido')
      .required('CPF é obrigatório'),
    phone: Yup.string()
      .matches(/^\(\d{2}\) \d{4,5}-\d{4}$/, 'Telefone inválido')
      .required('Telefone é obrigatório'),
  }),
  payment: Yup.object().shape({
    metodo: Yup.string().required('Selecione um método de pagamento'),
    nomeCartao: Yup.string().when('metodo', {
      is: 'cartao',
      then: (schema) => schema
        .min(2, 'Nome deve ter pelo menos 2 caracteres')
        .required('Nome no cartão é obrigatório'),
      otherwise: (schema) => schema.notRequired(),
    }),
    parcelas: Yup.string().when('metodo', {
      is: 'cartao',
      then: (schema) => schema.required('Selecione o número de parcelas'),
      otherwise: (schema) => schema.notRequired(),
    }),
  }),
});


const PixPayment = ({ idTransaction, qrcode, qr_code_image_url }: { idTransaction: string, qrcode: string, qr_code_image_url: string }) => {

  const toast = useToast();
  const { setting, produto } = useConfig();

  const copyQr = async () => {
    try {
      await navigator.clipboard.writeText(qrcode)
      toast({
        title: 'Chave pix copiada para área de transferência.',
        status: 'success',
        duration: 5000,
        isClosable: true,
      });
    } catch (err) {
      console.error('Erro ao copiar:', err)
    }
  }


  return (
    <VStack
      w={'100%'}
      gap={4}
    >
      <Text>Escaneie o QrCode ou copie o código abaixo</Text>
      <Center>
        <QRCode
          value={qrcode}
          logoImage={Helper.storageUrl(setting?.favicon_light) ?? undefined}
          qrStyle='dots'
          logoPaddingStyle='circle'
          eyeRadius={10}
          size={250}
          logoPadding={8}
        />
      </Center>
      <Input
        cursor={'pointer'}
        value={qrcode}
        readOnly
        onClick={copyQr}
        w={'100%'}
      />
    </VStack>
  );

}

const Confirmed = () => {
  const { produto } = useConfig();
  const [count, setCount] = useState<number>(5);

  useEffect(() => {
    if (!produto?.thankyou_page) return;

    if (count === 0) {
      window.location.href = produto?.thankyou_page; // redireciona
      return;
    }

    const timer = setTimeout(() => {
      setCount((prev) => prev - 1);
    }, 1000);

    return () => clearTimeout(timer);
  }, [count, produto?.thankyou_page]);

  return (
    <VStack w="100%" gap={4}>
      <Icon
        as={LucideCheckCircle2}
        boxSize={20}
        color={"white"}
        __css={{ fill: "green.500" }}
      />
      <Text color={"green.500"}>Pagamento confirmado!</Text>
      {produto?.thankyou_page && (
        <Text color={"green.500"}>
          Redirecionando em {count} segundos
        </Text>
      )}
    </VStack>
  );
};

const ShowCard = (props: any) => {
  const { produto } = useConfig();
  const [count, setCount] = useState<number>(5);

  useEffect(() => {
    
    if (count === 0) {
      let redirecionar = produto?.thankyou_page || window.location.href;
      if (props?.uuid) {
        redirecionar = `${redirecionar}?order=${props?.uuid}`;
      }
      window.location.href = redirecionar; // redireciona
      return;
    }

    const timer = setTimeout(() => {
      setCount((prev) => prev - 1);
    }, 1000);

    return () => clearTimeout(timer);
  }, [count, produto?.thankyou_page]);

  return (
    <VStack w="100%" gap={4}>
      <Icon
        as={LucideCheckCircle2}
        boxSize={20}
        color={"white"}
        __css={{ fill: "green.500" }}
      />
      <Text color={"green.500"}>Pagamento confirmado!</Text>
      {produto?.thankyou_page && (
        <Text color={"green.500"}>
          Redirecionando em {count} segundos
        </Text>
      )}
    </VStack>
  );
};

const ShowBoleto = (data: any) => {
  const toast = useToast();

  // Copiar código de barras
  const copiarBarcode = async () => {
    if (data?.barcode) {
      await navigator.clipboard.writeText(data.barcode);
      toast({
        title: 'Código de barras copiado com sucesso!',
        status: 'success',
        duration: 3000,
        isClosable: true,
      });
    }
  };

  // Download do boleto
  const downloadBoleto = () => {
    if (data?.download) {
      const link = document.createElement('a');
      link.href = data.download;
      link.download = 'boleto.pdf'; // Nome sugerido
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    }
  };

  return (
    <VStack w="100%" gap={4}>
      <Input
        w="full"
        value={data?.barcode}
        isReadOnly
        cursor="pointer"
        onClick={copiarBarcode} // Copia ao clicar no input
      />
      <ButtonGroup w="full" gap={3} mb={3}>
        <Button
          w="100%"
          colorScheme="green"
          leftIcon={<Icon as={LucideCopy} />}
          onClick={copiarBarcode}
        >
          Copiar
        </Button>
        <Button
          w="100%"
          colorScheme="green"
          leftIcon={<Icon as={LucideDownload} />}
          onClick={downloadBoleto}
        >
          Download
        </Button>
      </ButtonGroup>
      <Button
        w="100%"
        colorScheme="red"
        onClick={() => window.location.reload()}
      >
        Sair
      </Button>
    </VStack>
  );
};

export function ProductCheckoutForm() {
  const { template, setting, produto, getTotalPrice, selectedOrderBumps, fbq, stripe, affiliate_ref } = useConfig();
  const toast = useToast();
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [title, setTitle] = useState<string>('');
  const [content, setContent] = useState<any>();

  const { isOpen, onClose, onOpen } = useDisclosure();

  const handleQrCode = (props: any) => {
    setTitle('Pagamento via Pix');
    setContent(<PixPayment {...props} />);
    onOpen();
  }

  const handleBoleto = (data: any) => {
    setTitle('Pagamento via Boleto');
    setContent(<ShowBoleto {...data} />);
    onOpen();
  }

  const handleCard = (data: any) => {
    setTitle('Pagamento via Cartão');
    setContent(<ShowCard {...data} />);
    onOpen();
  }

  const initialValues: CheckoutFormValues = {
    buyer: {
      name: '',
      email: '',
      cpf: '',
      phone: '',
    },
    payment: {
      metodo: 'pix',
      numero: '',
      validade: '',
      cvv: '',
      parcelas: '1',
      nomeCartao: '',
      installments: 1,
      payment_token: ''
    },
  };

  const handleSubmit = async (values: CheckoutFormValues, formikHelpers: FormikHelpers<CheckoutFormValues>) => {
    console.log(values)
    setIsSubmitting(true);
    fbq && fbq('track', 'AddPaymentInfo');
    if (content && content instanceof PixPayment) {
      onOpen();
      return;
    }

    let data = {
      produto: produto,
      bumps: selectedOrderBumps,
      preco: getTotalPrice(),
      comprador: values.buyer,
      pagamento: values.payment,
      affiliate_ref
    }

    if (produto?.desconto && produto?.desconto > 0) {
      data['pagamento']['desconto'] = produto?.desconto;
    }

    if (values.payment.metodo == 'cartao' && setting?.adquirencia_card === 'efi') {
      //@ts-ignore
      let cardNumber = values?.payment?.numero.replace(' ', '');
      let valorCompra = getTotalPrice() * 100;
      // try {

      const brand = await EfiPay.CreditCard
        .setCardNumber(cardNumber)
        .verifyCardBrand();


      const payload_payment_token = {
        brand: brand,
        //@ts-ignore
        number: values.payment.numero.replace(/\D/g, ''),
        cvv: values.payment?.cvv,
        //@ts-ignore
        expirationMonth: values.payment.validade.split('/')[0],
        //@ts-ignore
        expirationYear: "20" + values.payment.validade.split('/')[1],
        holderName: values.payment.nomeCartao,
        holderDocument: values.buyer.cpf.replace(/\D/g, ''),
        reuse: false,
      };


      const result = await EfiPay.CreditCard
        .setAccount(setting?.efi_id_account as string)
        .setEnvironment("production") // 'production' or 'sandbox'
        //@ts-ignore
        .setCreditCardData(payload_payment_token)
        .getPaymentToken();
      //@ts-ignore
      const payment_token = result.payment_token;

      data.pagamento.payment_token = payment_token;
    }

    if (values.payment.metodo == 'cartao' && setting?.adquirencia_card === 'stripe') {

      //@ts-ignore
      const { error, paymentIntent } = await stripe?.stripe?.confirmCardPayment(
        stripe?.clientSecret,
        stripe?.payload
      );

      if (error) {
        let dados: any = data;
        dados['status'] = 'cancelado';
        dados['description'] = error.message;
        let response = await fetch('/api/pedido/order/stripe', {
          method: "POST",
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(data)
        });

        let res = await response.json();
        
        toast({
          title: 'Pagamento não realizado!',
          description: `${res?.message || 'Pagamento não aprovado pela sua operadora de cartão. Tente novamente!'}`,
          status: 'warning',
          duration: 8000,
          isClosable: true,
        });

      } else if (paymentIntent?.status === "succeeded") {

        let dados: any = data;
        dados['status'] = 'pago';
        dados['description'] = 'Pagamento realizado com sucesso!';
        let response = await fetch('/api/pedido/order/stripe', {
          method: "POST",
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(data)
        });

        let res = await response.json();

        fbq && fbq('track', 'Purchase', { value: getTotalPrice(), currency: 'BRL' });
        handleCard(res)
        toast({
          title: 'Pedido realizado com sucesso!',
          description: `Seu pedido foi processado. Você receberá um email de confirmação em ${values.buyer.email}`,
          status: 'success',
          duration: 5000,
          isClosable: true,
        });
      }

      return;
    }

    let response = await fetch('/api/pedido/order', {
      method: "POST",
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    });

    let res = await response.json();

    if (values?.payment?.metodo === 'pix' && res.idTransaction) {
      fbq && fbq('track', 'AddToCart');
      handleQrCode(res);

      const intervalId = setInterval(async () => {
        try {
          const statusResponse = await fetch('/api/status', {
            method: "POST",
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ idTransaction: res.idTransaction })
          });

          const statusData = await statusResponse.json();

          if (statusData.status === 'pago') {
            fbq && fbq('track', 'Purchase', { value: getTotalPrice(), currency: 'BRL' });
            clearInterval(intervalId);
            setContent(<Confirmed />);
            setTitle("Tudo Certo!")
            toast({
              title: 'Pedido realizado com sucesso!',
              description: `Seu pedido foi processado. Você receberá um email de confirmação em ${values.buyer.email}`,
              status: 'success',
              duration: 5000,
              isClosable: true,
            });
          }

        } catch (error) {
          console.error('Erro ao verificar status:', error);
        }
      }, 3000); // 3 segundos
    } else if (values?.payment?.metodo === 'cartao') {
      if (res.status) {
        fbq && fbq('track', 'Purchase', { value: getTotalPrice(), currency: 'BRL' });
        handleCard(res)
        toast({
          title: 'Pedido realizado com sucesso!',
          description: `Seu pedido foi processado. Você receberá um email de confirmação em ${values.buyer.email}`,
          status: 'success',
          duration: 5000,
          isClosable: true,
        });
      } else {
        setIsSubmitting(false);
        formikHelpers.setFieldValue('payment.numero', '');
        formikHelpers.setFieldValue('payment.validade', '');
        formikHelpers.setFieldValue('payment.cvv', '');

        toast({
          title: 'Pagamento não realizado!',
          description: `${res?.message || 'Pagamento não aprovado pela sua operadora de cartão. Tente novamente!'}`,
          status: 'warning',
          duration: 8000,
          isClosable: true,
        });
      }
    } else if (values?.payment?.metodo === 'boleto') {
      if (res.status) {
        fbq && fbq('track', 'Purchase', { value: getTotalPrice(), currency: 'BRL' });
        handleBoleto(res);
        toast({
          title: 'Boleto gerado!',
          description: `Carregando as informações do boleto.`,
          status: 'success',
          duration: 3000,
          isClosable: true,
        });
      } else {
        setIsSubmitting(false);
        toast({
          title: 'Houve um erro!',
          description: `Erro ao gerar o boleto. Varias tentativas com os mesmos dados. Tente com novos dados!'`,
          status: 'warning',
          duration: 5000,
          isClosable: true,
        });
      }
    }

  };

  return (
    <StripeWrapper>
      <VStack spacing={6} align="stretch">
        {/* Resumo do Pedido */}
        <OrderSummary />

        <Divider />

        <Formik
          initialValues={initialValues}
          validationSchema={setting?.adquirencia_card === 'stripe' ? validationSchemaStripe : validationSchema}
          validateOnChange
          validateOnBlur
          onSubmit={handleSubmit}
        >
          {({ values, setFieldValue, errors, touched }) => (
            <Form>
              <VStack spacing={6} align="stretch">
                {/* Dados do Comprador */}
                <BuyerForm
                  onSubmit={(buyerData) => {
                    setFieldValue('buyer', buyerData);
                  }}
                />

                <Divider />


                <ProductPaymentForm
                  onSubmit={(paymentData) => {
                    setFieldValue('payment', paymentData);
                  }}
                  isSubmitting={isSubmitting}
                />
                {/* Dados de Pagamento */}

              </VStack>
            </Form>
          )}
        </Formik>

        <Modal onClose={onClose} isOpen={isOpen} isCentered >
          <ModalOverlay />
          <ModalContent mx={{ base: 4, lg: undefined }}>
            <ModalHeader textAlign={'center'}>{title}</ModalHeader>
            <ModalCloseButton />
            <ModalBody mb={10}>
              {content}
            </ModalBody>
          </ModalContent>
        </Modal>
      </VStack>
    </StripeWrapper>
  );
}