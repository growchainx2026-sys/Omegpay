import {
  Grid, GridItem, Box, HStack, Text,
  Image as ChakraImage,
  Link
} from '@chakra-ui/react'
import { TextComponent } from './renderable/TextComponent'
import { ImageComponent } from './renderable/ImageComponent'
import { VantagemComponent } from './renderable/VantagemComponent'
import { SeloComponent } from './renderable/SeloComponent'
import { HeaderComponent } from './renderable/HeaderComponent'
import { ListaComponent } from './renderable/ListaComponent'
import { ContadorComponent } from './renderable/ContadorComponent'
import { DepoimentoComponent } from './renderable/DepoimentoComponent'
import useConfig from '../stores/config'
import CheckoutOffer from './ui/Footerpayment'
import { UserDataForm } from './UserDataForm'
import { PaymentForm } from './PaymentForm'

export function MainViewer() {
  const { checkoutData, template, device } = useConfig()
  
  // Obter rows do layout do checkoutData
  const rows = checkoutData?.layout?.rows || []

  const handlePaymentSubmit = (paymentData: any) => {
  }

  return (
    <HStack
      justifyContent={'center'}
      w={'100%'}
      bg="black.800"
      p={4}
    >
      <GridItem
        area="main"
        p={8}
        px={device === 'desktop' ? { base: 2, desktop: 8 } : 0}
        overflowY="auto"
        borderRadius={10}
        bg={template.bg}
        w={device === 'desktop' ? '100%' : '480px'}
        transition={'width ease 0.3s'}
      >
        {/* Renderizar as rows sem funcionalidades de edição */}
        {rows && rows.length > 0 ? rows.map((row) => (
          <Box key={row.id}>
            <Grid
              templateColumns={row.layout || '1fr'}
              gap={4}
              p={4}
              px={0}
              bg="transparent"
              borderRadius="lg"
              mb={8}
              minH={'80px'}
            >
              {row.components && row.components.length > 0 ? row.components.map((gridItem: any, gridItemIndex: number) => (
                <GridItem key={gridItemIndex} p={4} borderRadius="md">
                  {/* Renderizar componentes dentro do GridItem */}
                  {gridItem?.components?.length > 0 ? (
                    gridItem.components.map((component: any) => (
                      <Box key={component.id}>
                        {component.type === 'text' && (
                          <TextComponent
                            component={component}
                            handleComponentClick={() => {}}
                          />
                        )}
                        {component.type === 'image' && (
                          <ImageComponent
                            component={component}
                            handleComponentClick={() => {}}
                          />
                        )}
                        {component.type === 'vantagem' && (
                          <VantagemComponent
                            component={component}
                            handleComponentClick={() => {}}
                          />
                        )}
                        {component.type === 'selo' && (
                          <SeloComponent
                            component={component}
                            handleComponentClick={() => {}}
                          />
                        )}
                        {component.type === 'header' && (
                          <HeaderComponent
                            component={component}
                            handleComponentClick={() => {}}
                          />
                        )}
                        {component.type === 'lista' && (
                          <ListaComponent
                            component={component}
                            handleComponentClick={() => {}}
                          />
                        )}
                        {component.type === 'contador' && (
                          <ContadorComponent
                            component={component}
                            handleComponentClick={() => {}}
                          />
                        )}
                        {component.type === 'depoimento' && (
                          <DepoimentoComponent
                            component={component}
                            handleComponentClick={() => {}}
                          />
                        )}
                      </Box>
                    ))
                  ) : (
                    <Box
                      minH="80px"
                      display="flex"
                      alignItems="center"
                      justifyContent="center"
                      color="gray.400"
                      fontSize="sm"
                    >
                      {/* Área vazia - sem conteúdo */}
                    </Box>
                  )}
                </GridItem>
              )) : (
                <GridItem p={4} borderRadius="md">
                  <Box
                    minH="80px"
                    display="flex"
                    alignItems="center"
                    justifyContent="center"
                    color="gray.400"
                    fontSize="sm"
                  >
                    {/* Área vazia - sem conteúdo */}
                  </Box>
                </GridItem>
              )}
            </Grid>
          </Box>
        )) : (
          <Box
            minH="200px"
            display="flex"
            alignItems="center"
            justifyContent="center"
            color="gray.400"
            fontSize="lg"
          >
            Nenhum conteúdo disponível
          </Box>
        )}

        {/* Seção de checkout */}
        <Box mt={8}>
          <Grid
            templateColumns="1fr 1fr"
            gap={8}
            bg={template.bg}
            borderRadius="lg"
            p={6}
            boxShadow="lg"
          >
            <GridItem>
              {/* Formulário movido para o CheckoutOffer */}
            </GridItem>
            <GridItem>
              <Box bg={template.sidebar_bg} p={6} borderRadius="lg" boxShadow="sm">
                <Box mb={6}>
                  <Text fontSize="lg" fontWeight="bold" color={template.text_primary} mb={4}>
                    {checkoutData?.produto_name || "Produto"}
                  </Text>
                  <Text fontSize="sm" color={template.text_secondary} mb={2}>
                    Precisa de ajuda?
                  </Text>
                  <Text fontSize="sm" color={template.text_secondary}>
                    Veja o contato do vendedor
                  </Text>
                </Box>

                <Box mb={6}>
                  <Text fontSize="lg" fontWeight="bold" color={template.text_primary} mb={2}>
                    Total
                  </Text>
                  <Text fontSize="2xl" fontWeight="bold" color={template.text_primary}>
                    1 X de {checkoutData ? `R$ ${checkoutData.produto_price.toFixed(2).replace('.', ',')}` : "R$ 0,00"}
                  </Text>
                  <Text fontSize="sm" color={template.text_secondary}>
                    ou {checkoutData ? `R$ ${checkoutData.produto_price.toFixed(2).replace('.', ',')}` : "R$ 0,00"} à vista
                  </Text>
                  <Text fontSize="sm" color={template.text_secondary} mt={2}>Renovação atual</Text>
                </Box>

                <CheckoutOffer
                  metodo="cartao"
                  onSubmit={handlePaymentSubmit}
                  isSubmitting={false}
                />

                <Box mt={6}>
                  <ChakraImage src="/favicon.png" alt="Gokto" height="20px" mb={4} />
                  <Text fontSize="xs" color={template.text_secondary} mb={2}>
                    Space é uma instituição de pagamento para o comércio eletrônico regulada pelo Banco Central do Brasil e protegida pela
                    <Link color={template.text_secondary} href="#"> Política de privacidade </Link>
                    e
                    <Link color={template.text_secondary} href="#"> Termos de serviço</Link>.
                  </Text>
                  <Text fontSize="xs" color={template.text_secondary}>
                    Para reclamações sobre serviços financeiros, você também pode entrar em contato com os
                    <Link color={template.text_secondary} href="#"> Termos de Compra</Link>.
                  </Text>
                </Box>
              </Box>
            </GridItem>
          </Grid>
        </Box>
      </GridItem>
    </HStack>
  )
}