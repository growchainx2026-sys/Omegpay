import { useState, useEffect, useCallback } from 'react';
import { Box, VStack, HStack, Grid, GridItem } from '@chakra-ui/react';
import { useConfig } from '../stores/config';
import { TextComponent } from './renderable/TextComponent';
import { ImageComponent } from './renderable-produto/ImageComponent';
import { VantagemComponent } from './renderable/VantagemComponent';
import { SeloComponent } from './renderable-produto/SeloComponent';
import { HeaderComponent } from './renderable-produto/HeaderComponent';
import { ListaComponent } from './renderable/ListaComponent';
import { ContadorComponent } from './renderable-produto/ContadorComponent';
import { DepoimentoComponent } from './renderable-produto/DepoimentoComponent';

interface Row {
  id: string;
  layout: string;
  components: any[];
}

export function ProductMain() {
  const { checkout } = useConfig();
  const [rows, setRows] = useState<Row[]>([]);

  // Função para converter layout dinâmico em rows
  const convertLayoutToRows = useCallback((layout: any) => {
    return layout.rows.map((layoutRow: any) => {
      const gridColumns = Array.from({ length: layoutRow.columns }, () => ({ components: [] }));

      layoutRow.components.forEach((component: any, index: number) => {
        const columnIndex = index % layoutRow.columns;

        const convertedComponent = {
          id: component.id,
          type: component.type,
          props: component.props, // Preservar todas as props do componente
        };

        (gridColumns[columnIndex].components as any[]) = [convertedComponent]; // Garantir que apenas um componente seja permitido por gridItem
      });

      return {
        id: layoutRow.id,
        layout: `repeat(${layoutRow.columns}, 1fr)`,
        components: gridColumns,
      };
    });
  }, []);

  // Inicializar rows com base no checkout
  useEffect(() => {
    if (checkout?.layout) {
      const convertedRows = convertLayoutToRows(checkout.layout);
      setRows(convertedRows);
    } else {
      setRows([]);
    }
  }, [checkout, convertLayoutToRows]);

  // Função para renderizar os componentes
  const renderComponent = (component: any) => {
    const commonProps = {
      ...component.props,
      isEditing: false, // Sempre false para a página do produto
      onEdit: () => { }, // Função vazia
      onDelete: () => { }, // Função vazia
    };

    switch (component.type) {

      case 'text':
        return <TextComponent key={component.id} component={commonProps} handleComponentClick={() => { }} />;
      case 'image':
        return <ImageComponent key={component.id} component={commonProps} handleComponentClick={() => { }} />;
      case 'vantagem':
        return <VantagemComponent key={component.id} component={commonProps} handleComponentClick={() => { }} />;
      case 'selo':
        return <SeloComponent key={component.id} component={commonProps} handleComponentClick={() => { }} />;
      case 'header':
        return <HeaderComponent key={component.id} component={commonProps} handleComponentClick={() => { }} />;
      case 'lista':
        return <ListaComponent key={component.id} component={commonProps} handleComponentClick={() => { }} />;
      case 'contador':
        return <ContadorComponent key={component.id} component={commonProps} handleComponentClick={() => { }} />;
      case 'depoimento':
        return <DepoimentoComponent key={component.id} component={commonProps} handleComponentClick={() => { }} />;
      default:
        return null;
    }
  };

  // Função para renderizar uma linha
  const renderRow = (row: any) => {
    const columns = row.components.length;

    if (columns === 1) {
      return (
        <HStack spacing={4}  w="full" px={2}>
          {row.components[0].components.map((component: any) => renderComponent(component))}
        </HStack>
      );
    }

    if (columns === 2) {
      return (
        <Grid templateColumns="repeat(12, 1fr)" gap={6} w="100%" px={2}>

          {row.components.map((gridItem: any) =>
            gridItem.components.map((component: any) => {
              return (
                <GridItem w={'100%'} key={component.id} colSpan={{ base: 12, xl: 6 }} display={'flex'} justifyContent={component?.props?.align}>
                  {renderComponent(component)}
                </GridItem>
              );
            })
          )}
        </Grid>
      );
    }

    // Para mais de 2 colunas, usar Grid com repeat
    return (
      <Grid templateColumns={`repeat(12, 1fr)`} gap={4} w="100%" px={2}>
        {row.components.map((gridItem: any) =>
          gridItem.components.map((component: any) => (
            <GridItem key={component.id} colSpan={{ base: 12, xl: 4 }}>
              {renderComponent(component)}
            </GridItem>
          ))
        )}
      </Grid>
    );
  };

  return (
    <VStack spacing={6} align="start"  mx={0} 
          overflowX={'hidden'}>
      {rows.map((row) => (
        <Box key={row.id} w="100%">
          {renderRow(row)}
        </Box>
      ))}
    </VStack>
  );
}