import React from 'react';
import { Box, Grid, GridItem, Text, Image } from '@chakra-ui/react';
import type { Layout, LayoutComponent, LayoutRow } from '../services/api';

interface DynamicLayoutProps {
  layout: Layout;
}

// Componente para renderizar um componente individual
const RenderComponent: React.FC<{ component: LayoutComponent }> = ({ component }) => {
  switch (component.type) {
    case 'text':
      return (
        <Text
          fontSize={component.props.fontSize || '16px'}
          fontWeight={component.props.fontWeight || 'normal'}
          color={component.props.color || '#000000'}
          textAlign={component.props.textAlign || 'left'}
        >
          {component.props.content}
        </Text>
      );
    
    case 'image':
      return (
        <Image
          src={component.props.src}
          alt={component.props.alt || 'Imagem'}
          width={component.props.width || 'auto'}
          height={component.props.height || 'auto'}
          objectFit={component.props.objectFit || 'cover'}
          borderRadius={component.props.borderRadius || '0'}
        />
      );
    
    case 'button':
      return (
        <Box
          as="button"
          px={component.props.px || 4}
          py={component.props.py || 2}
          bg={component.props.bg || '#007bff'}
          color={component.props.color || '#ffffff'}
          borderRadius={component.props.borderRadius || 'md'}
          fontSize={component.props.fontSize || '16px'}
          fontWeight={component.props.fontWeight || 'medium'}
          cursor="pointer"
          _hover={{
            bg: component.props.hoverBg || '#0056b3'
          }}
        >
          {component.props.content}
        </Box>
      );
    
    default:
      return (
        <Box p={2} bg="gray.100" borderRadius="md">
          <Text fontSize="sm" color="gray.500">
            Componente não reconhecido: {component.type}
          </Text>
        </Box>
      );
  }
};

// Componente para renderizar uma linha do layout
const RenderRow: React.FC<{ row: LayoutRow }> = ({ row }) => {
  return (
    <Grid
      templateColumns={`repeat(${row.columns}, 1fr)`}
      gap={4}
      mb={4}
      w="100%"
    >
      {row.components.map((component) => (
        <GridItem key={component.id} colSpan={1}>
          <RenderComponent component={component} />
        </GridItem>
      ))}
    </Grid>
  );
};

// Componente principal do layout dinâmico
export const DynamicLayout: React.FC<DynamicLayoutProps> = ({ layout }) => {
  return (
    <Box w="100%">
      {layout.rows.map((row) => (
        <RenderRow key={row.id} row={row} />
      ))}
    </Box>
  );
};

export default DynamicLayout;