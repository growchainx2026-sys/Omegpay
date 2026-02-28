import {
  Box,
  HStack,
  Text,
  Icon,
  Button,
  VStack,
  Stack,
  Grid,
  GridItem,
  FormControl,
  FormLabel,
  Input
} from '@chakra-ui/react'
import { CreditCard, QrCode, Barcode, CheckCircle, ShieldCheck, TicketPercent } from 'lucide-react'
import { FaPix } from 'react-icons/fa6'
import { useConfig } from '../stores/config'
import CheckoutOffer from './ui/Footerpayment'

interface PaymentFormProps {
  metodo: 'pix' | 'boleto' | 'cartao';
  onMetodoChange: (metodo: 'pix' | 'boleto' | 'cartao') => void;
  cardData?: {
    numero?: string;
    validade?: string;
    cvv?: string;
    parcelas?: string;
  };
  onCardDataChange?: (field: string, value: string) => void;
}

export function PaymentForm({
  metodo,
  onMetodoChange,
  cardData,
  onCardDataChange
}: PaymentFormProps) {
  const { template, produto } = useConfig();

  const handleCardInputChange = (field: string, value: string) => {
    if (onCardDataChange) {
      onCardDataChange(field, value);
    }
  };

  return (
    <Box p={6} borderRadius="lg" w={'100%'}>
      <HStack spacing={2} mb={6} align="center">
        <Icon as={CreditCard} boxSize={5} color={template.icon_color} />
        <Text fontWeight="bold" color={template.text_primary}>Pagamento</Text>
      </HStack>

      <Grid templateColumns="repeat(12, 1fr)" mb={4} gap={4} w={'100%'}>
        {(produto?.methods ?? []).includes('pix') && (
          <GridItem colSpan={4} w={'100%'}>
            <Button
              bg={metodo === 'pix' ? template.btn_selected_bg_color : template.btn_unselected_bg_color}
              color={metodo === 'pix' ? template.btn_selected_text_color : template.btn_unselected_text_color}
              colorScheme='teal'
              variant={metodo === 'pix' ? 'solid' : 'outline'}
              onClick={() => onMetodoChange('pix')}
              size="lg"
              flex={1}
              minH={'120px'}
              w={'100%'}
              _hover={{
                borderColor: 'teal',
                boxShadow: 'lg',
                transform: 'translateY(-2px)'
              }}
            >
              <VStack w={'100%'} gap={4}>
                <Icon as={FaPix} boxSize={6} color={metodo === 'pix' ? template.btn_selected_icon_color : template.btn_unselected_icon_color} />
                <Text fontSize={16}>PIX</Text>
              </VStack>
            </Button>
          </GridItem>
        )}

        {(produto?.methods ?? []).includes('boleto') && (
          <GridItem colSpan={4} w={'100%'}>
            <Button
              bg={metodo === 'boleto' ? template.btn_selected_bg_color : template.btn_unselected_bg_color}
              color={metodo === 'boleto' ? template.btn_selected_text_color : template.btn_unselected_text_color}
              colorScheme='teal'
              variant={metodo === 'boleto' ? 'solid' : 'outline'}
              onClick={() => onMetodoChange('boleto')}
              size="lg"
              flex={1}
              w={'100%'}
              minH={'120px'}
              _hover={{
                borderColor: 'teal',
                boxShadow: 'lg',
                transform: 'translateY(-2px)'
              }}
            >
              <VStack w={'100%'} gap={4}>
                <Icon as={Barcode} boxSize={6} color={metodo === 'boleto' ? template.btn_selected_icon_color : template.btn_unselected_icon_color} />
                <Text fontSize={16}>Boleto</Text>
              </VStack>
            </Button>
          </GridItem>
        )}

        {(produto?.methods ?? []).includes('credit_card') && (
          <GridItem colSpan={4} w={'100%'}>
            <Button
              bg={metodo === 'cartao' ? template.btn_selected_bg_color : template.btn_unselected_bg_color}
              color={metodo === 'cartao' ? template.btn_selected_text_color : template.btn_unselected_text_color}
              colorScheme='teal'
              variant={metodo === 'cartao' ? 'solid' : 'outline'}
              onClick={() => onMetodoChange('cartao')}
              size="lg"
              w={'100%'}
              flex={1}
              minH={'120px'}
              _hover={{
                borderColor: 'teal',
                boxShadow: 'lg',
                transform: 'translateY(-2px)'
              }}
            >
              <VStack w={'100%'} gap={4}>
                <Icon as={CreditCard} boxSize={6} color={metodo === 'cartao' ? template.btn_selected_icon_color : template.btn_unselected_icon_color} />
                <Text fontSize={16}>Cartão</Text>
              </VStack>
            </Button>
          </GridItem>
        )}
      </Grid>

      {/* Conteúdo específico por método de pagamento */}
      {metodo === 'pix' && (
        <VStack
          w={'100%'}
          p={5}
          bg={'white'}
          borderRadius={8}
          borderWidth={2}
          borderColor={'gray.300'}
          borderStyle={'dashed'}
          alignItems={'flex-start'}
          gap={0}
          lineHeight={'30px'}
        >
          <Text color={template.text_primary}>
            <Icon as={CheckCircle} color={'green.500'} />
            {' '}Liberação imediata
          </Text>
          <Text color={template.text_primary}>
            <Icon as={CheckCircle} color={'green.500'} />
            {' '}É simples, só usar o aplicativo de seu banco para pagar Pix
          </Text>
        </VStack>
      )}

      {metodo === 'boleto' && (
        <HStack
          w={'100%'}
          borderRadius={6}
          bg={'orange.600'}
          color={'orange.100'}
          p={4}
          gap={8}
        >
          <Icon as={Barcode} boxSize={6} color={template.icon_color} />
          <Text>
            Boletos podem levar 3 dias úteis para serem efetivados após o pagamento.
          </Text>
        </HStack>
      )}

      {metodo === 'cartao' && (
        <>
          <Grid templateColumns={'repeat(12, 1fr)'} gap={3}>
            <GridItem colSpan={{ base: 12 }}>
              <FormControl>
                <FormLabel fontSize={12} color={template.text_secondary}>Número do cartão</FormLabel>
                <Input
                  type='text'
                  value={cardData?.numero || '5125 1456 5641 4010'}
                  color={template.text_primary}
                  onChange={(e) => handleCardInputChange('numero', e.target.value)}
                />
              </FormControl>
            </GridItem>
            <GridItem colSpan={{ base: 12, lg: 3 }}>
              <FormControl>
                <FormLabel fontSize={12} color={template.text_secondary}>Validade</FormLabel>
                <Input
                  type='text'
                  value={cardData?.validade || '10/25'}
                  color={template.text_primary}
                  onChange={(e) => handleCardInputChange('validade', e.target.value)}
                />
              </FormControl>
            </GridItem>
            <GridItem colSpan={{ base: 12, lg: 3 }}>
              <FormControl>
                <FormLabel fontSize={12} color={template.text_secondary}>CVV</FormLabel>
                <Input
                  type='text'
                  value={cardData?.cvv || '123'}
                  color={template.text_primary}
                  onChange={(e) => handleCardInputChange('cvv', e.target.value)}
                />
              </FormControl>
            </GridItem>
            <GridItem colSpan={{ base: 12, lg: 6 }}>
              <FormControl>
                <FormLabel fontSize={12} color={template.text_secondary}>Parcelas</FormLabel>
                <Input
                  type='text'
                  value={cardData?.parcelas || '1 x R$ 100,00'}
                  color={template.text_primary}
                  onChange={(e) => handleCardInputChange('parcelas', e.target.value)}
                />
              </FormControl>
            </GridItem>
          </Grid>
          <GridItem my={2} colSpan={12}>
            <HStack w={'100%'} gap={2}>
              <Icon as={ShieldCheck} boxSize={6} color={template.icon_color} __css={{ fill: 'black' }} />
              <Text color={template.text_secondary}>
                Os seus dados de pagamento são criptografados e processados de forma segura.
              </Text>
            </HStack>
          </GridItem>
        </>
      )}

      <HStack
        mt={5}
        mb={-5}
        w={'100%'}
        gap={2}
        fontWeight={'bold'}
      >
        <Icon as={TicketPercent} boxSize={6} color={'white'} __css={{ fill: template.icon_color }} />
        <Text color={template.text_primary}>
          Oferta limitada
        </Text>
      </HStack>

      <CheckoutOffer metodo={metodo} />
    </Box>
  );
}