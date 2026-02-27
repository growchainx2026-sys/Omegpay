import React from 'react';
import { useSortable } from '@dnd-kit/sortable';
import { CSS } from '@dnd-kit/utilities';
import { Box, Grid } from '@chakra-ui/react';
import useConfig from '@/stores/config';

interface SortableRowProps {
  id: string;
  children: React.ReactNode;
  dragOverPosition?: { rowId: string; position: 'above' | 'below' } | null;
  activeRowId?: string | null;
  draggedLayoutType?: string | null;
  isDraggingComponent?: boolean;
  onListenersReady?: (listeners: any) => void;
}

export function SortableRow({ 
  id, 
  children, 
  dragOverPosition, 
  activeRowId, 
  draggedLayoutType,
  isDraggingComponent,
  onListenersReady 
}: SortableRowProps) {
  const {
    attributes,
    listeners,
    setNodeRef,
    transform,
    transition,
    isDragging,
  } = useSortable({ id });

  const {template} = useConfig();

  React.useEffect(() => {
    if (onListenersReady) {
      onListenersReady(listeners);
    }
  }, [listeners, onListenersReady]);

  const style = {
    transform: CSS.Transform.toString(transform),
    transition,
  };

  const renderDropPreview = () => {
    // Só mostrar preview se:
    // 1. Há um tipo de layout sendo arrastado
    // 2. Não é uma linha sendo reordenada (activeRowId é null)
    // 3. Está em modo de arrastar componente
    // 4. Há uma posição de drag over definida
    if (!draggedLayoutType || activeRowId || !isDraggingComponent || !dragOverPosition) return null;

    // Determinar o layout baseado no tipo arrastado
    let layoutConfig = {
      templateColumns: '1fr',
      columnCount: 1,
      columns: [{ span: 12 }]
    };
    
    if (draggedLayoutType === 'layout-1') {
      layoutConfig = {
        templateColumns: '1fr',
        columnCount: 1,
        columns: [{ span: 12 }]
      };
    } else if (draggedLayoutType === 'layout-2') {
      layoutConfig = {
        templateColumns: 'repeat(12, 1fr)',
        columnCount: 2,
        columns: [{ span: 9 }, { span: 3 }]
      };
    } else if (draggedLayoutType === 'layout-3') {
      layoutConfig = {
        templateColumns: 'repeat(12, 1fr)',
        columnCount: 2,
        columns: [{ span: 3 }, { span: 9 }]
      };
    } else if (draggedLayoutType === 'layout-4') {
      layoutConfig = {
        templateColumns: 'repeat(12, 1fr)',
        columnCount: 3,
        columns: [{ span: 4 }, { span: 4 }, { span: 4 }]
      };
    }

    return (
      <Box
        w="100%"
        border="2px solid #4A5568"
        borderRadius="12px"
        mb={3}
        bg="#2D3748"
        p={3}
        minH="120px"
      >
        <Grid
          templateColumns={layoutConfig.templateColumns}
          gap={3}
          h="100%"
        >
          {layoutConfig.columns.map((col, index) => (
            <Box
              key={index}
              gridColumn={`span ${col.span}`}
              bg="#4A5568"
              borderRadius="8px"
              border="1px solid #718096"
              display="flex"
              alignItems="center"
              justifyContent="center"
              fontSize="lg"
              color="white"
              fontWeight="bold"
              minH="100px"
            >
              {index + 1}
            </Box>
          ))}
        </Grid>
      </Box>
    );
  };

  return (
    <Box
      ref={setNodeRef}
      style={style}
      {...attributes}
      opacity={isDragging ? 0.5 : 1}
    >
      {/* Preview acima da linha */}
      {dragOverPosition && dragOverPosition.rowId === id && dragOverPosition.position === 'above' && (
        renderDropPreview()
      )}
      
      {children}
      
      {/* Preview abaixo da linha */}
      {dragOverPosition && dragOverPosition.rowId === id && dragOverPosition.position === 'below' && (
        renderDropPreview()
      )}
    </Box>
  );
}