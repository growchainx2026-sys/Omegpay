import { Grid, GridItem } from '@chakra-ui/react';
import { useEffect, useState } from 'react';
import { Main } from '../components/Main';
import { Header } from '../components/Header';
import { Sidebar } from '../components/Sidebar';
import { Checkout } from '@/types/checkout';
import { Template } from '@/types/template';
import { Produto } from '@/types/produto';
import { Setting } from '@/types/setting';
import useConfig from '@/stores/config';
import { DrawerSidebar } from '@/components/ui/DrawerSidebar';

export interface CheckoutPageProps {
  checkout: Checkout
  produto?: Produto
  setting: Setting
  vendedor: string
}

export default function CheckoutPage({ setting, produto, checkout, vendedor }: CheckoutPageProps) {
  const [isDraggingComponent, setIsDraggingComponent] = useState(false);
  const [currentLayoutType, setCurrentLayoutType] = useState<string | null>(null);
  const { setSetting, setProduto, setCheckout, setTemplate, setVendedor, setDepoimentos, convertDepoimentosToTemplate } = useConfig();

  useEffect(() => {
    setSetting(setting);
    setProduto(produto);
    setCheckout(checkout);
    setTemplate(checkout?.template as Template);
    setVendedor(vendedor);
    setDepoimentos(checkout?.depoimentos || []);
  }, []);
  return (
    <Grid
      templateAreas={{
        base: `"header header header"
                      "divider divider divider"
                      "main main main"`,
        lg: `"header header header"
                      "divider divider divider"
                      "main main sidebar"`}}
      gridTemplateRows="64px 2px 1fr"
      gridTemplateColumns="1fr 1fr 320px"
      h="100vh"
      w="100vw"
      bg="gray.900"
      overflow="hidden"
      overflowX={{ base: 'auto', lg: 'hidden' }}
      m={0}
      p={0}
    >
      <GridItem area="header" >
        <Header />
      </GridItem>
      <GridItem area="divider">
        <div style={{ height: '2px', background: '#0b6856', width: '100%' }} />
      </GridItem>
      <GridItem area="main" overflow="auto" px={{ base: 4, lg: 20 }} pt={10}
        minW={{ base: '1080px', lg: undefined }} pos={'relative'}>
        <Main
          isDraggingComponent={isDraggingComponent}
          setIsDraggingComponent={setIsDraggingComponent}
          currentLayoutType={currentLayoutType}
        />
        <DrawerSidebar
          onDragStart={(layoutType) => {
            setIsDraggingComponent(true);
            setCurrentLayoutType(layoutType || null);
            if (layoutType && (window as any).handleLayoutDragStart) {
              (window as any).handleLayoutDragStart(layoutType);
            }
          }}
          onDragEnd={() => {
            setIsDraggingComponent(false);
            setCurrentLayoutType(null);
          }}
        />
      </GridItem>
      <GridItem area="sidebar" overflow="auto" display={{ base: 'none', lg: 'block' }}>
        <Sidebar
          onDragStart={(layoutType) => {
            setIsDraggingComponent(true);
            setCurrentLayoutType(layoutType || null);
            if (layoutType && (window as any).handleLayoutDragStart) {
              (window as any).handleLayoutDragStart(layoutType);
            }
          }}
          onDragEnd={() => {
            setIsDraggingComponent(false);
            setCurrentLayoutType(null);
          }}
        />
      </GridItem>
    </Grid>
  )
}
