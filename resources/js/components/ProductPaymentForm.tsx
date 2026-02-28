import {
  Box,
  HStack,
  Text,
  Icon,
  Button,
  VStack,
  Grid,
  GridItem,
  FormControl,
  FormLabel,
  Input,
  FormErrorMessage,
  Select,
  SelectProps,
  Heading,
} from '@chakra-ui/react';
import { CreditCard, Barcode, ShieldCheck, TicketPercent } from 'lucide-react';
import { FaPix } from 'react-icons/fa6';
import { Field, useFormikContext } from 'formik';
import * as Yup from 'yup';
import { useConfig } from '../stores/config';
import CheckoutOffer from './ui/Footerpayment';
import { Helper } from '@/helpers/helpers';
import { useCallback, useEffect, useRef, useState } from 'react';
import EfiPay from "payment-token-efi";
import { StripeWrapper } from './customs/StripeWrapper';
import { StripeForm } from './customs/StripeForm';

interface PaymentFormValues {
  metodo: 'pix' | 'boleto' | 'cartao';
  numero?: string;
  validade?: string;
  cvv?: string;
  parcelas?: string;
  nomeCartao?: string;
}

const validationSchema = Yup.object().shape({
  metodo: Yup.string().required('Selecione um método de pagamento'),
  numero: Yup.string().when('metodo', {
    is: 'cartao',
    then: (schema) =>
      schema
        .matches(/^\d{4} \d{4} \d{4} \d{4}$/, 'Número do cartão inválido')
        .required('Número do cartão é obrigatório'),
  }),
  validade: Yup.string().when('metodo', {
    is: 'cartao',
    then: (schema) =>
      schema
        .matches(/^\d{2}\/\d{2}$/, 'Validade inválida (MM/AA)')
        .required('Validade é obrigatória'),
  }),
  cvv: Yup.string().when('metodo', {
    is: 'cartao',
    then: (schema) =>
      schema.matches(/^\d{3,4}$/, 'CVV inválido').required('CVV é obrigatório'),
  }),
  nomeCartao: Yup.string().when('metodo', {
    is: 'cartao',
    then: (schema) =>
      schema.min(2, 'Nome deve ter pelo menos 2 caracteres').required('Nome no cartão é obrigatório'),
  }),
  parcelas: Yup.string().when('metodo', {
    is: 'cartao',
    then: (schema) => schema.required('Selecione o número de parcelas'),
  }),
});

interface ProductPaymentFormProps {
  onSubmit?: (data: any) => void;
  isSubmitting?: boolean;
}

export function ProductPaymentForm({ onSubmit, isSubmitting }: ProductPaymentFormProps = {}) {
  const { template, produto, setting, getTotalPrice, getTotalBruto, setItemProduto } = useConfig();
  const { values, setFieldValue } = useFormikContext<any>();
  const parcelasRef = useRef<SelectProps>({});

  const [parcelas, setParcelas] = useState<{ label: String, value: string }[]>([{ value: '' + getTotalPrice(), label: `À vista - ${Helper.formatPrice(getTotalPrice())}` }]);

  useEffect(() => {

    if (setting?.adquirencia_card !== 'efi' && setting?.adquirencia_card !== 'pagarme') return;

    const { numero, validade, cvv } = values.payment || {};

    if (numero && validade && cvv && cvv.length === 3) {
      getInstallments();
    } else {
      parcelasRef.current.disabled = true;
    }
  }, [setting?.adquirencia_card, values.payment?.numero, values.payment?.validade, values.payment?.cvv]);

  const getInstallments = async () => {
    if (setting?.adquirencia_card === 'efi') {
      let cardNumber = values?.payment?.numero.replace(' ', '');
      let valorCompra = getTotalPrice() * 100;
      // try {

      const brand = await EfiPay.CreditCard
        .setCardNumber(cardNumber)
        .verifyCardBrand();

      const installments = await EfiPay.CreditCard
        .setAccount(setting?.efi_id_account as string)
        .setEnvironment("production") // 'production' or 'sandbox'
        .setBrand(brand)
        .setTotal(valorCompra)
        .getInstallments();

      let options: any[] = [];
      setParcelas([]);
      //@ts-ignore
      installments.installments.forEach((data) => {
        const label = data?.installment === 1 ? `À vista - ${Helper.formatPrice(getTotalPrice())}` :
          `${data?.installment}x de ${Helper.formatPrice(Number(data?.value / 100))}`;
        options.push({ value: data?.installment, label });
      })

      setParcelas(options as any);


      parcelasRef.current.disabled = false;
    } else if (setting?.adquirencia_card == 'pagarme') {
      fetch(`/api/pagarme/parcels/${getTotalBruto()}`,
        {
          'method': 'GET'
        })
        .then((res) => res.json())
        .then((res) => {
          console.log(res)
          setParcelas(res);
          parcelasRef.current.disabled = false;
        })
    }

  }

  const maskCardNumber = (value: string) => {
    return value
      .replace(/\D/g, '')          // remove tudo que não é número
      .slice(0, 16)                // limita a 16 dígitos
      .replace(/(\d{4})(?=\d)/g, '$1 '); // adiciona espaço a cada 4 dígitos
  };

  // Máscara para validade MM/AA
  const maskCardExpiry = (value: string) => {
    return value
      .replace(/\D/g, '')
      .slice(0, 4)
      .replace(/(\d{2})(?=\d)/, '$1/');
  };

  // Máscara para CVV
  const maskCardCvv = (value: string) => {
    return value.replace(/\D/g, '').slice(0, 4);
  };

  const [erro, setErro] = useState<string | null>(null);
  const [cupom, setCupom] = useState<any>(null);
  const [enviando, setEnviando] = useState<boolean>(false);
  const [blockCupom, setBlockCupom] = useState<boolean>(false);

  const verificaCupom = async () => {
    if (!cupom || cupom.lenght == 0) {
      setErro('Digite o cupom antes de aplicar');
      return;
    }

    setEnviando(true);

    let response = await fetch('/api/pedido/cupom/verificar', {
      method: "POST",
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ cupom, produto_id: produto?.id })
    });

    let res = await response.json();
    if (!res.status) {
      setErro(res.message);
      setEnviando(false)
      return;
    }

    setItemProduto(res.data);
    getTotalPrice();
    getTotalBruto();

    setBlockCupom(true);
    setEnviando(false)
    console.log(res)

  }

  const liberarCampoCupom = () => {
    setItemProduto({ desconto: 0, desconto_bumps: false, desconto_id: undefined });
    getTotalPrice();
    setCupom('');
    setErro(null);
    setBlockCupom(false);
  }

  return (
      <Box p={2} borderRadius="lg" w={'100%'}>
        <HStack spacing={2} mb={6} align="center">
          <Icon as={CreditCard} boxSize={5} color={template.icon_color} />
          <Text fontWeight="bold" color={template.text_primary}>Pagamento</Text>
        </HStack>

        {/* Seleção de método */}
        <Field name="payment.metodo">
          {({ meta }: any) => (
            <FormControl isInvalid={meta.error && meta.touched} w={'100%'}>
              <Grid templateColumns="repeat(12, 1fr)" mb={4} gap={4} w={'100%'}>
                {(produto?.methods ?? []).includes('pix') && (
                  <GridItem colSpan={4}>
                    <Button
                      w="100%"
                      bg={values.payment.metodo === 'pix' ? template.btn_selected_bg_color : template.btn_unselected_bg_color}
                      color={values.payment.metodo === 'pix' ? template.btn_selected_text_color : template.btn_unselected_text_color}
                      variant={values.payment.metodo === 'pix' ? 'solid' : 'outline'}
                      onClick={() => setFieldValue('payment.metodo', 'pix')}
                      minH="120px"
                      _hover={{ boxShadow: 'lg', transform: 'translateY(-2px)' }}
                    >
                      <VStack gap={4}>
                        <Icon as={FaPix} boxSize={6} color={values.payment.metodo === 'pix' ? template.btn_selected_icon_color : template.btn_unselected_icon_color} />
                        <Text fontSize={16}>PIX</Text>
                      </VStack>
                    </Button>
                  </GridItem>
                )}
                {(produto?.methods ?? []).includes('boleto') && (
                  <GridItem colSpan={4}>
                    <Button
                      w="100%"
                      bg={values.payment.metodo === 'boleto' ? template.btn_selected_bg_color : template.btn_unselected_bg_color}
                      color={values.payment.metodo === 'boleto' ? template.btn_selected_text_color : template.btn_unselected_text_color}
                      variant={values.payment.metodo === 'boleto' ? 'solid' : 'outline'}
                      onClick={() => setFieldValue('payment.metodo', 'boleto')}
                      minH="120px"
                      _hover={{ boxShadow: 'lg', transform: 'translateY(-2px)' }}
                    >
                      <VStack gap={4}>
                        <Icon as={Barcode} boxSize={6} color={values.payment.metodo === 'boleto' ? template.btn_selected_icon_color : template.btn_unselected_icon_color} />
                        <Text fontSize={16}>Boleto</Text>
                      </VStack>
                    </Button>
                  </GridItem>
                )}
                {(produto?.methods ?? []).includes('cartao') && (
                  <GridItem colSpan={4}>
                    <Button
                      w="100%"
                      bg={values.payment.metodo === 'cartao' ? template.btn_selected_bg_color : template.btn_unselected_bg_color}
                      color={values.payment.metodo === 'cartao' ? template.btn_selected_text_color : template.btn_unselected_text_color}
                      variant={values.payment.metodo === 'cartao' ? 'solid' : 'outline'}
                      onClick={() => setFieldValue('payment.metodo', 'cartao')}
                      minH="120px"
                      _hover={{ boxShadow: 'lg', transform: 'translateY(-2px)' }}
                    >
                      <VStack gap={4}>
                        <Icon as={CreditCard} boxSize={6} color={values.payment.metodo === 'cartao' ? template.btn_selected_icon_color : template.btn_unselected_icon_color} />
                        <Text fontSize={16}>Cartão</Text>
                      </VStack>
                    </Button>
                  </GridItem>
                )}
              </Grid>
              <FormErrorMessage>{meta.error}</FormErrorMessage>
            </FormControl>
          )}
        </Field>

        {/* Conteúdo do método selecionado */}
        <VStack spacing={4} align="stretch">
          {values.payment.metodo === 'cartao' && (
            <>
              {setting?.adquirencia_card === 'stripe' ? (

                <StripeForm
                  onSubmit={onSubmit}
                  isSubmitting={isSubmitting}
                />
              ) : (
                <>
                  <Field name="payment.nomeCartao">
                    {({ field, meta }: any) => (
                      <FormControl isInvalid={meta.error && meta.touched}>
                        <FormLabel fontSize={12} color={template.text_secondary}>Nome no cartão</FormLabel>
                        <Input {...field} placeholder="Nome como está no cartão" color={template.text_primary} />
                        <FormErrorMessage>{meta.error}</FormErrorMessage>
                      </FormControl>
                    )}
                  </Field>

                  <Grid templateColumns={'repeat(12, 1fr)'} gap={3}>
                    <GridItem colSpan={{ base: 12 }}>
                      <Field name="payment.numero">
                        {({ field, form, meta }: any) => {
                          const handleChange = useCallback(
                            (e: React.ChangeEvent<HTMLInputElement>) => {
                              const masked = maskCardNumber(e.target.value);
                              form.setFieldValue(field.name, masked);
                            },
                            [form, field.name]
                          );

                          return (
                            <FormControl isInvalid={!!meta.error && meta.touched}>
                              <FormLabel fontSize={12} color={template.text_secondary}>Número do cartão</FormLabel>
                              <Input
                                {...field}
                                onChange={handleChange}
                                placeholder="0000 0000 0000 0000"
                              />
                              <FormErrorMessage>{meta.error}</FormErrorMessage>
                            </FormControl>
                          );
                        }}
                      </Field>
                    </GridItem>

                    <GridItem colSpan={{ base: 6, lg: 3 }}>
                      <Field name="payment.validade">
                        {({ field, form, meta }: any) => {
                          const handleChange = useCallback(
                            (e: React.ChangeEvent<HTMLInputElement>) => {
                              const masked = maskCardExpiry(e.target.value);
                              form.setFieldValue(field.name, masked);
                            },
                            [form, field.name]
                          );

                          return (
                            <>
                              <FormLabel fontSize={12} color={template.text_secondary}>Validade</FormLabel>
                              <Input {...field} onChange={handleChange} placeholder="MM/AA" />
                            </>
                          );
                        }}
                      </Field>
                    </GridItem>

                    <GridItem colSpan={{ base: 6, lg: 3 }}>
                      <Field name="payment.cvv">
                        {({ field, form, meta }: any) => {
                          const handleChange = useCallback(
                            (e: React.ChangeEvent<HTMLInputElement>) => {
                              const masked = maskCardCvv(e.target.value);
                              form.setFieldValue(field.name, masked);
                            },
                            [form, field.name]
                          );

                          return (
                            <>
                              <FormLabel fontSize={12} color={template.text_secondary}>CVV</FormLabel>
                              <Input {...field} onChange={handleChange} placeholder="123" />
                            </>
                          );
                        }}
                      </Field>
                    </GridItem>

                    <GridItem colSpan={{ base: 12, lg: 6 }}>
                      <Field name="payment.parcelas">
                        {({ field, meta }: any) => (
                          <FormControl isInvalid={meta.error && meta.touched}>
                            <FormLabel fontSize={12} color={template.text_secondary}>Parcelas</FormLabel>
                            <Select {...field} color={template.text_primary} ref={parcelasRef} disabled>
                              {parcelas.map((option) => (
                                <option key={option.value} value={option.value}>
                                  {option.label}
                                </option>
                              ))}
                            </Select>
                            <FormErrorMessage>{meta.error}</FormErrorMessage>
                          </FormControl>
                        )}
                      </Field>
                    </GridItem>
                  </Grid>
                </>
              )
              }


              <HStack w={'100%'} gap={2}>
                <Icon as={ShieldCheck} boxSize={6} color={template.icon_color} />
                <Text color={template.text_secondary}>
                  Os seus dados de pagamento são criptografados e processados de forma segura.
                </Text>
              </HStack>
            </>
          )}

          {/* Outras informações */}
          <HStack mt={5} mb={-5} w={'100%'} gap={2} fontWeight={'bold'}>
            <Icon as={TicketPercent} boxSize={6} color={'white'} __css={{ fill: template.icon_color }} />
            <Text color={template.text_primary}>Oferta limitada</Text>
          </HStack>

          <Grid
            templateColumns={'repeat(12, 1fr)'}
            my={3}
            w={'full'}
            gap={2}>
            <GridItem
              colSpan={{ base: 9, lg: 9 }}
            >
              <Input
                disabled={blockCupom ? true : false}
                placeholder='DIGITE O CUPOM DE DESCONTO'
                value={cupom}
                borderColor={erro ? 'red.400' : blockCupom ? 'green' : undefined}
                color={erro ? 'red.400' : blockCupom ? 'green' : undefined}
                fontWeight={blockCupom ? 'bold' : undefined}
                _hover={{
                  borderColor: erro ? 'red.400' : blockCupom ? 'green' : undefined,
                }}
                onChange={(e) => { setCupom(e.target.value); setErro('') }} />
            </GridItem>
            <GridItem
              colSpan={{ base: 3, lg: 3 }}
            >
              <Button
                w={'full'}
                colorScheme="green"
                border={'none'}
                color={template.btn_payment_text_color}
                bg={blockCupom ? 'red' : template.btn_payment_bg_color}
                isLoading={enviando}
                loadingText="Verificando..."
                onClick={blockCupom ? liberarCampoCupom : verificaCupom}
                _hover={{
                  bg: blockCupom ? 'red' : template.btn_payment_bg_color,
                  color: template.btn_payment_text_color,
                  border: 'none',
                  boxShadow: 'lg',
                  transform: 'translateY(-2px)'
                }}>{blockCupom ? 'Remover' : 'Aplicar'}</Button>
            </GridItem>
            {erro && (
              <GridItem
                colSpan={12}
              >
                <Text as={'small'} color={'red.400'} fontWeight={'bold'}>* {erro}</Text>
              </GridItem>

            )}
          </Grid>

          <CheckoutOffer metodo={values.payment.metodo} isSubmitting={isSubmitting} />

        </VStack >
      </Box >
  );
}
