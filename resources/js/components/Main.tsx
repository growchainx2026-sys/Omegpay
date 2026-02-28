import {
  Grid, GridItem, Box, HStack, Text,
  Image as ChakraImage, Link,
  VStack,
  useToast
} from '@chakra-ui/react'
import { useState, useEffect, useCallback } from 'react'
import React from 'react'
import { textData } from '../data/text-data'
import { imageData } from '../data/image-data'
import { vantagemData } from '../data/vantagem-data'
import { seloData } from '../data/selo-data'
import { headerData } from '../data/header-data'
import { listaData } from '../data/lista-data'
import { contadorData } from '../data/contador-data'
import { depoimentoData } from '../data/depoimento-data'
import { TextComponent } from './renderable/TextComponent'
import { ImageComponent } from './renderable/ImageComponent'
import { VantagemComponent } from './renderable/VantagemComponent'
import { SeloComponent } from './renderable/SeloComponent'
import { HeaderComponent } from './renderable/HeaderComponent'
import { ListaComponent } from './renderable/ListaComponent'
import { ContadorComponent } from './renderable/ContadorComponent'
import { DepoimentoComponent } from './renderable/DepoimentoComponent'
import type { Component, ImageComponentProps } from '../types/components'
import useConfig from '../stores/config'
import { Drawers } from './drawers'
import { v4 as uuidv4 } from 'uuid'; // Para gerar IDs únicos
import { ComponentOptionsRow } from './ui/ComponentOptionsRow'
import { ComponentOptionsComponent } from './ui/ComponentOptionsComponent'
import { SortableRow } from './ui/SortableRow'
import { LinhaDrawer } from './drawers/LinhaDrawer'
import { UserDataForm } from './UserDataForm'
import { PaymentForm } from './PaymentForm'
import { Helper } from '@/helpers/helpers'
import { Template } from '@/types/template'
import { DndContext, closestCenter, DragEndEvent, DragOverlay, DragStartEvent } from '@dnd-kit/core'
import { SortableContext, verticalListSortingStrategy, arrayMove, useSortable } from '@dnd-kit/sortable'
import { CSS } from '@dnd-kit/utilities'
import { restrictToVerticalAxis } from '@dnd-kit/modifiers'
import { Move } from 'lucide-react'

interface MainProps {
  isDraggingComponent: boolean
  setIsDraggingComponent: (value: boolean) => void
  onLayoutDragStart?: (layoutType: string) => void
  currentLayoutType?: string | null
}

export function Main({ isDraggingComponent, setIsDraggingComponent, onLayoutDragStart, currentLayoutType }: MainProps) {

  const toast = useToast();

  // Set currentDraggedLayout immediately when currentLayoutType changes
  React.useEffect(() => {
    if (currentLayoutType) {
      setCurrentDraggedLayout(currentLayoutType);
      setDraggedLayoutType(currentLayoutType);
    }
  }, [currentLayoutType]);
  const { template, device, checkout, produto, setting, setLayout, setTemplate, depoimentos, setDepoimentos, convertDepoimentosToTemplate, convertTemplateToDepoimentos } = useConfig();



  const convertRowsToLayout = useCallback((rows: any[]) => {
    return {
      rows: rows?.map((row) => {
        const components = row.components.flatMap((gridItem: any) =>
          gridItem.components.map((component: any) => {
            const baseComponent = {
              id: component?.id,
              type: component?.type,
              props: {},
            };

            if (component?.type === 'text') {
              baseComponent.props = {
                content: component?.text || 'Texto',
                color: component?.color || '#000000',
                fontSize: `${component?.fontSize || 16}px`,
              };
            } else if (component?.type === 'image') {
              baseComponent.props = {
                src: component?.url || '',
                align: component?.align || 'center',
                size: component?.size || 250,
                redirectUrl: component?.redirectUrl || '',
                background: component?.background || 'transparent',
              };
            } else if (component?.type === 'vantagem') {
              baseComponent.props = {
                title: component?.title || 'Título da Vantagem',
                description: component?.description || 'Descrição da Vantagem',
                icon: component?.icon || '',
                subtitle: component?.subtitle || '',
                title_color: component?.title_color || '',
                subtitle_color: component?.subtitle_color || '',
                mode: component?.mode || '',
                background: component?.background || '',
              };
            } else if (component?.type === 'selo') {
              baseComponent.props = {
                label: component?.label || 'Selo',
                icon: component?.icon || '',
                selo: component?.selo || '',
                header: component?.header || '',
                title: component?.title || '',
                subtitle: component?.subtitle || '',
                title_color: component?.title_color || '',
                color: component?.color || '',
                align: component?.align || 'center',
                background: component?.background || '',
              };
            } else if (component?.type === 'header') {
              baseComponent.props = {
                text: component?.text || 'Cabeçalho',
                size: component?.size || 'lg',
                title: component?.title || '',
                color: component?.color || '',
                fontSize: component?.fontSize || '',
                image: component?.image || '',
                align: component?.align || 'left',
                padding: component?.padding || '',
                background: component?.background || '',
              };
            } else if (component?.type === 'lista') {
              baseComponent.props = {
                items: component?.items || [],
                icone: component?.icone || 'check',
                corIcone: component?.corIcone || 'green',
                temTitulo: component?.temTitulo || false,
                titulo: component?.titulo || '',
                corFundo: component?.corFundo || '',
                corTexto: component?.corTexto || '',
                alinhamento: component?.alinhamento || 'left',
                tamanho: component?.tamanho || '',
                background: component?.background || '',
              };
            } else if (component?.type === 'contador') {
              baseComponent.props = {
                start: component?.start || 0,
                end: component?.end || 100,
                duration: component?.duration || 2,
                tipo: component?.tipo || 'time',
                time: component?.time || '',
                textActive: component?.textActive || '',
                textFinalizado: component?.textFinalizado || '',
                textColor: component?.textColor || '',
                bgColor: component?.bgColor || '',
              };
            } else if (component?.type === 'depoimento') {
              baseComponent.props = {
                author: component?.author || 'Autor',
                content: component?.content || 'Depoimento',
                photo: component?.photo || '',
                depoimento: component?.depoimento || '',
                estrelas: component?.estrelas || '',
                nome: component?.nome || '',
                corFundo: component?.corFundo || '',
                corTexto: component?.corTexto || '',
                modoHorizontal: component?.modoHorizontal || false,
              };
            }

            return baseComponent;
          })
        );

        return {
          id: row.id,
          columns: row.components.length,
          components,
        };
      }),
    };
  }, []);
  // Função para converter layout dinâmico em rows editáveis
  const convertLayoutToRows = useCallback((layout: any) => {
    return layout?.rows?.map((layoutRow: any) => {
      const gridColumns = Array.from({ length: layoutRow.columns }, () => ({ components: [] }));

      layoutRow?.components?.forEach((component: any, index: number) => {
        const columnIndex = index % layoutRow.columns;

        const convertedComponent = {
          id: component?.id,
          type: component?.type,
          ...(component?.type === 'text' && {
            text: component?.props.content || 'Texto',
            color: component?.props.color || '#000000',
            fontSize: parseInt(component?.props.fontSize?.replace('px', '')) || 16,
            borderColor: component?.props.borderColor || '#000000',
            backgroundColor: component?.props.backgroundColor || 'transparent',
            borderWidth: component?.props.borderWidth || 0,
            borderRadius: component?.props.borderRadius || 0,
            background: component?.props.background || 'transparent',
          }),
          ...(component?.type === 'image' && {
            url: component?.props.src || '',
            align: component?.props.align || 'center',
            size: component?.props.size || '250px',
            redirectUrl: component?.props.redirectUrl || '',
            background: component?.props.background || 'transparent',
          }),
          ...(component?.type === 'vantagem' && {
            title: component?.props.title || 'Título da Vantagem',
            description: component?.props.description || 'Descrição da Vantagem',
            icon: component?.props.icon || '',
            subtitle: component?.props.subtitle || '',
            title_color: component?.props.title_color || '',
            subtitle_color: component?.props.subtitle_color || '',
            mode: component?.props.mode || '',
            background: component?.props.background || '',
          }),
          ...(component?.type === 'selo' && {
            label: component?.props.label || 'Selo',
            icon: component?.props.icon || '',
            selo: component?.props.selo || '',
            header: component?.props.header || '',
            title: component?.props.title || '',
            subtitle: component?.props.subtitle || '',
            title_color: component?.props.title_color || '',
            color: component?.props.color || '',
            align: component?.props.align || 'center',
            background: component?.props.background || '',
          }),
          ...(component?.type === 'header' && {
            text: component?.props.text || 'Cabeçalho',
            size: component?.props.size || 'lg',
            title: component?.props.title || '',
            color: component?.props.color || '',
            fontSize: component?.props.fontSize || '',
            image: component?.props.image || '',
            align: component?.props.align || 'left',
            padding: component?.props.padding || '',
            background: component?.props.background || '',
          }),
          ...(component?.type === 'lista' && {
            items: component?.props.items || [],
            icone: component?.props.icone || 'check',
            corIcone: component?.props.corIcone || 'green',
            temTitulo: component?.props.temTitulo || false,
            titulo: component?.props.titulo || '',
            corFundo: component?.props.corFundo || '',
            corTexto: component?.props.corTexto || '',
            alinhamento: component?.props.alinhamento || 'left',
            tamanho: component?.props.tamanho || '',
            background: component?.props.background || '',
          }),
          ...(component?.type === 'contador' && {
            start: component?.props.start || 0,
            end: component?.props.end || 100,
            duration: component?.props.duration || 2,
            tipo: component?.props.tipo || 'time',
            time: component?.props.time || '',
            textActive: component?.props.textActive || '',
            textFinalizado: component?.props.textFinalizado || '',
            textColor: component?.props.textColor || '',
            bgColor: component?.props.bgColor || '',
          }),
          ...(component?.type === 'depoimento' && {
            author: component?.props.author || 'Autor',
            content: component?.props.content || 'Depoimento',
            photo: component?.props.photo || '',
            depoimento: component?.props.depoimento || '',
            estrelas: component?.props.estrelas || '',
            nome: component?.props.nome || '',
            corFundo: component?.props.corFundo || '',
            corTexto: component?.props.corTexto || '',
            modoHorizontal: component?.props.modoHorizontal || false,
          }),
        };

        // Garantir que apenas um componente seja permitido por gridItem
        (gridColumns[columnIndex].components as any[]) = [convertedComponent];
      });

      setBackground('transparent')
      return {
        id: layoutRow.id,
        layout: `repeat(${layoutRow.columns}, 1fr)`,
        components: gridColumns,
      };
    });
  }, []);

  const [rows, setRows] = useState<any[]>(() => {
    if (checkout?.layout) {
      return convertLayoutToRows(checkout.layout);
    }
    return [
      {
        id: uuidv4(),
        layout: '1fr',
        components: [{ components: [] }],
      },
    ];
  });

  useEffect(() => {
    if (checkout?.layout) {
      setRows(convertLayoutToRows(checkout.layout));
    }
  }, [checkout, convertLayoutToRows]);

  // Inicializar depoimentos do checkout quando for carregado
  useEffect(() => {
    // Sempre garantir que depoimentos seja um array
    setDepoimentos(checkout?.depoimentos || []);
  }, [checkout, setDepoimentos]);

  // Salvar depoimentos no template quando forem atualizados
  useEffect(() => {
    if ((depoimentos || []).length > 0) {
      const templateDepoimentos = convertDepoimentosToTemplate(depoimentos || []);
      setTemplate({ depoimentos: templateDepoimentos.depoimentos });
    }
  }, [depoimentos, convertDepoimentosToTemplate, setTemplate]);



  const [hoveredRowId, setHoveredRowId] = useState<string | null>(null);
  const [hoveredComponentId, setHoveredComponentId] = useState<string | null>(null);
  const [selectedRowId, setSelectedRowId] = useState<string | null>(null)
  const [components, setComponents] = useState<Component[] | any>([])
  const [selectedComponent, setSelectedComponent] = useState<Component | null>(null)
  const [isDraggingOver, setIsDraggingOver] = useState(false)
  const [isDrawerOpen, setIsDrawerOpen] = useState(false)
  const [isEditDrawerOpen, setIsEditDrawerOpen] = useState(false);
  const [editingRowId, setEditingRowId] = useState<string | null>(null);
  const [background, setBackground] = useState<any>('gray.800');
  const [draggedLayoutType, setDraggedLayoutType] = useState<string | null>(null);
  const [showLayoutPreview, setShowLayoutPreview] = useState<boolean>(false);
  const [draggedOverGridItem, setDraggedOverGridItem] = useState<{ rowId: string, gridIndex: number } | null>(null);
  const [activeRowId, setActiveRowId] = useState<string | null>(null);
  const [dragOverPosition, setDragOverPosition] = useState<{ rowId: string, position: 'above' | 'below' } | null>(null);
  const [currentDraggedLayout, setCurrentDraggedLayout] = useState<string | null>(null);
  const [metodo, setMetodo] = useState<'pix' | 'boleto' | 'cartao'>('pix');
  const [exibe, setExibe] = useState<boolean>(false);
  const [isDraggingDepoimento, setIsDraggingDepoimento] = useState<boolean>(false);

  const [formData, setFormData] = useState({
    nome: '',
    email: '',
    cpf: '',
    celular: ''
  });
  const [cardData, setCardData] = useState({
    numero: '',
    validade: '',
    cvv: '',
    parcelas: ''
  });

  const handleDragStart = (event: DragStartEvent) => {
    setActiveRowId(event.active.id as string);
  };

  const handleRowDragOver = (event: React.DragEvent, rowId: string) => {
    event.preventDefault();

    // Verificar se é um layout sendo arrastado
    const types = event.dataTransfer.types;
    if (types.includes('text/plain')) {
      const rect = event.currentTarget.getBoundingClientRect();
      const mouseY = event.clientY;

      // Detecção mais intuitiva: metade superior = acima, metade inferior = abaixo
      const componentMiddle = rect.top + rect.height / 2;

      let position: 'above' | 'below';
      if (mouseY <= componentMiddle) {
        position = 'above';
      } else {
        position = 'below';
      }

      // Sempre atualizar a posição de drag over
      setDragOverPosition({ rowId, position });

      // Se não é uma linha sendo arrastada (activeRowId é null), é um layout do sidebar
      // Só mostrar preview se não estiver arrastando uma linha
      if (!activeRowId && isDraggingComponent && (currentDraggedLayout || draggedLayoutType)) {
        // Usar o tipo de layout real sendo arrastado
        const layoutType = currentDraggedLayout || draggedLayoutType;
        setDraggedLayoutType(layoutType);
        setShowLayoutPreview(true);
      } else if (activeRowId) {
        // Se está arrastando uma linha, não mostrar preview de layout
        setDraggedLayoutType(null);
        setShowLayoutPreview(false);
      }
    }
  };

  const handleRowDragLeave = (event: React.DragEvent) => {
    // Verificar se realmente saiu da área da linha
    const rect = event.currentTarget.getBoundingClientRect();
    const x = event.clientX;
    const y = event.clientY;

    // Só limpar se realmente saiu da área da linha
    if (x < rect.left || x > rect.right || y < rect.top || y > rect.bottom) {
      // Não limpar imediatamente para evitar flickering
      setTimeout(() => {
        setDragOverPosition(null);
        setShowLayoutPreview(false);
      }, 50);
    }
  };

  // Função para lidar com o fim do drag and drop
  const handleDragEnd = (event: DragEndEvent) => {
    const { active, over } = event;

    // Reset imediato dos estados de drag
    setActiveRowId(null);
    setDragOverPosition(null);
    setShowLayoutPreview(false);
    setDraggedLayoutType(null);
    setCurrentDraggedLayout(null);

    if (!over) return;

    const activeId = active.id as string;
    const overId = over.id as string;

    if (activeId !== overId) {
      setRows((items) => {
        const oldIndex = items.findIndex((item) => item.id === activeId);
        let newIndex = items.findIndex((item) => item.id === overId);

        // Ajustar o índice baseado na posição do drop
        if (dragOverPosition && dragOverPosition.position === 'below') {
          newIndex = newIndex + 1;
        }

        return arrayMove(items, oldIndex, newIndex);
      });
    }
  };

  // Atualizar rows quando checkout mudar
  useEffect(() => {
    if (checkout?.layout) {
      setRows(convertLayoutToRows(checkout?.layout));
    }
  }, [checkout, convertLayoutToRows]);

  // Atualizar layout quando rows mudarem (para sincronizar após drag and drop)
  useEffect(() => {
    if (rows.length > 0) {
      const updatedLayout = convertRowsToLayout(rows);
      setLayout(updatedLayout);
    }
  }, [rows, convertRowsToLayout, setLayout]);

  const handleFormChange = (field: string, value: string) => {
    setFormData(prev => ({
      ...prev,
      [field]: value
    }));
  };

  const handleCardDataChange = (field: string, value: string) => {
    setCardData(prev => ({
      ...prev,
      [field]: value
    }));
  };

  const handleMetodoChange = (novoMetodo: 'pix' | 'boleto' | 'cartao') => {
    setMetodo(novoMetodo);
  };

  const handleRowDrop = (e: React.DragEvent, targetRowId: string) => {
    e.preventDefault();
    e.stopPropagation();

    const draggedData = e.dataTransfer.getData('text/plain');

    // Processar layouts da seção linhas do sidebar
    if (draggedData && draggedData.startsWith('layout-')) {
      const layoutNumber = draggedData.split('-')[1];
      let layout = '1fr';

      switch (layoutNumber) {
        case '1':
          layout = '1fr';
          break;
        case '2':
          layout = '2fr 1fr';
          break;
        case '3':
          layout = '1fr 2fr';
          break;
        case '4':
          layout = 'repeat(3, 1fr)';
          break;
      }

      // Criar nova linha com o layout especificado
      const columnCount = layoutNumber === '1' ? 1 : layoutNumber === '4' ? 3 : 2;
      const gridColumns = [];
      for (let i = 0; i < columnCount; i++) {
        gridColumns.push({ components: [] });
      }
      const newRow = {
        id: uuidv4(),
        layout: layout,
        components: gridColumns
      };

      // Encontrar a posição da linha alvo
      const targetIndex = rows.findIndex(row => row.id === targetRowId);

      // Determinar posição de inserção baseada na posição do mouse
      let insertIndex;
      if (dragOverPosition && dragOverPosition.rowId === targetRowId) {
        insertIndex = dragOverPosition.position === 'above' ? targetIndex : targetIndex + 1;
      } else {
        // Fallback: inserir após a linha alvo se não houver posição específica
        insertIndex = targetIndex + 1;
      }

      // Inserir a nova linha na posição correta
      const newRows = [...rows];
      newRows.splice(insertIndex, 0, newRow);

      setRows(newRows);
      const updatedLayout = convertRowsToLayout(newRows);
      setLayout(updatedLayout);

      // Reset drag states imediatamente após inserir a nova linha
      setDragOverPosition(null);
      setShowLayoutPreview(false);
      setDraggedLayoutType(null);
      setCurrentDraggedLayout(null);
      setIsDraggingComponent(false);
    }
  }

  const handleDrop = (e: React.DragEvent) => {
    e.preventDefault();

    const draggedData = e.dataTransfer.getData('text/plain');

    // Processar layouts da seção linhas do sidebar (apenas para drop na área vazia)
    if (draggedData && draggedData.startsWith('layout-')) {
      const layoutNumber = draggedData.split('-')[1];
      let layout = '1fr';

      switch (layoutNumber) {
        case '1':
          layout = '1fr';
          break;
        case '2':
          layout = '2fr 1fr';
          break;
        case '3':
          layout = '1fr 2fr';
          break;
        case '4':
          layout = 'repeat(3, 1fr)';
          break;
      }

      // Criar nova linha com o layout especificado
      const columnCount = layoutNumber === '1' ? 1 : layoutNumber === '4' ? 3 : 2;
      const gridColumns = [];
      for (let i = 0; i < columnCount; i++) {
        gridColumns.push({ components: [] });
      }
      const newRow = {
        id: uuidv4(),
        layout: layout,
        components: gridColumns
      };

      setRows([...rows, newRow]);
      const updatedLayout = convertRowsToLayout([...rows, newRow]);
      setLayout(updatedLayout);

      // Reset drag states imediatamente após inserir a nova linha
      setIsDraggingOver(false);
      setBackground('transparent');
      setShowLayoutPreview(false);
      setDraggedLayoutType(null);
      setCurrentDraggedLayout(null);
      setIsDraggingComponent(false);
    }
  }

  // Função para ser chamada quando um layout começar a ser arrastado do sidebar
  const handleLayoutDragStart = (layoutType: string) => {
    setCurrentDraggedLayout(layoutType);
    setDraggedLayoutType(layoutType);
    setIsDraggingComponent(true);
  };

  // Expor a função para o componente pai
  React.useEffect(() => {
    if (onLayoutDragStart) {
      // Esta é uma forma de "exportar" a função para o componente pai
      (window as any).handleLayoutDragStart = handleLayoutDragStart;
    }
  }, [onLayoutDragStart]);

  const handleDragOver = (e: React.DragEvent) => {
    e.preventDefault();
    e.dataTransfer.dropEffect = 'copy';
    setIsDraggingOver(true);

    // Durante dragover, os dados não estão disponíveis em alguns browsers
    // Vamos usar os tipos de dados para detectar o que está sendo arrastado
    const types = e.dataTransfer.types;

    if (types.includes('text/plain')) {
      // Verificar se já estamos em modo de arrastar componente
      if (!isDraggingComponent) {
        setIsDraggingComponent(true);
      }
    }
  }

  // Funções específicas para o dropzone de depoimentos
  const handleDepoimentoDragOver = (e: React.DragEvent) => {
    e.preventDefault();
    e.dataTransfer.dropEffect = 'copy';
  }

  const handleDepoimentoDrop = (e: React.DragEvent) => {
    e.preventDefault();
    const draggedData = e.dataTransfer.getData('text/plain');

    // Verificar se é um depoimento sendo arrastado
    if (draggedData === 'depoimento-input') {
      const newDepoimento = { ...depoimentoData, id: uuidv4() };
      setDepoimentos([...depoimentos, newDepoimento]);
      setIsDraggingDepoimento(false);
      setIsDraggingComponent(false);
    } else {
      // Se não for um depoimento, mostrar mensagem de erro
      toast({
        title: 'Alerta!',
        description: `Apenas depoimentos são permitidos nesta área`,
        status: 'warning',
        duration: 5000,
        isClosable: true,
      });
      //alert('');
    }
  }

  const handleDepoimentoClick = (depoimento: any) => {
    setSelectedComponent(depoimento);
    setIsDrawerOpen(true);
  }

  const handleDeleteDepoimento = (depoimentoId: string) => {
    setDepoimentos((depoimentos || []).filter(dep => dep.id !== depoimentoId));
  }

  const handleDragLeave = (e: React.DragEvent) => {
    // Verificar se realmente saiu da área de drop
    const rect = e.currentTarget.getBoundingClientRect();
    const x = e.clientX;
    const y = e.clientY;

    if (x < rect.left || x > rect.right || y < rect.top || y > rect.bottom) {
      setIsDraggingOver(false);
      setShowLayoutPreview(false);
      setDraggedLayoutType(null);
      setDraggedOverGridItem(null);
      // Não resetar isDraggingComponent aqui para evitar conflito com drop
      // setIsDraggingComponent(false);
      setCurrentDraggedLayout(null);
      setDragOverPosition(null);
    }
  }



  const handleDelete = () => {
    if (selectedComponent) {
      // Verificar se é um depoimento da área específica
      const isDepoimentoInSpecialArea = (depoimentos || []).some(dep => dep?.id === selectedComponent?.id);

      if (isDepoimentoInSpecialArea) {
        // Remover depoimento da área específica
        handleDeleteDepoimento(selectedComponent?.id);
      } else {
        // Remover componente das rows normais
        setRows((prevRows) =>
          prevRows.map((row) => ({
            ...row,
            components: row.components.map((gridItem: any) => ({
              ...gridItem,
              components: gridItem.components?.filter((component: Component) =>
                component?.id !== selectedComponent.id
              ) || []
            }))
          })).filter((row) =>
            // Remove linhas que ficaram sem componentes
            row.components.some((gridItem: any) => gridItem.components.length > 0)
          )
        );
      }

      setSelectedComponent(null);
      setIsDrawerOpen(false);
    }
  };

  const handleDuplicate = () => {
    if (selectedComponent) {
      const newComponent = {
        ...selectedComponent,
        id: uuidv4()
      };

      setRows((prevRows) =>
        prevRows.map((row) => ({
          ...row,
          components: row.components.map((gridItem: any) => {
            const hasComponent = gridItem.components?.some((component: Component) =>
              component?.id === selectedComponent.id
            );

            if (hasComponent) {
              return {
                ...gridItem,
                components: [newComponent] // Substituir componente existente pelo duplicado
              };
            }
            return gridItem;
          })
        }))
      );
    }
  }

  const handleDeleteRow = ({ id }: { id: string }) => {
    // Não permite excluir se houver apenas uma linha
    if (rows?.length <= 1) {
      return;
    }

    setRows(rows?.filter((row) => row.id !== id))
    setSelectedRowId(null)
    setHoveredRowId(null)
    setSelectedComponent(null)
  }

  const handleDuplicateRow = ({ id }: { id: string }) => {
    const rowToDuplicate = rows?.find((row) => row.id === id);
    if (rowToDuplicate) {
      const newRow = {
        ...rowToDuplicate,
        id: uuidv4(), // Gerar um novo ID único
        components: rowToDuplicate.components.map((component: Component) => ({
          ...component,
          id: uuidv4() // Gerar novos IDs para os componentes
        }))
      };
      setRows([...rows, newRow]);
    }
    setSelectedRowId(null)
    setHoveredRowId(null)
    setSelectedComponent(null)
  }

  const handleEditRow = (id: string) => {
    setEditingRowId(id);
    setIsEditDrawerOpen(true);
  };

  const updateComponent = (id: string, updates: Partial<Component>) => {
    // Verificar se é um depoimento da área específica
    const isDepoimentoInSpecialArea = (depoimentos || []).some(dep => dep.id === id);

    if (isDepoimentoInSpecialArea) {
      // Atualizar depoimento na área específica
      setDepoimentos(
        (depoimentos || []).map(dep => dep?.id === id ? { ...dep, ...updates } : dep)
      );
    } else {
      // Atualizar componente nas rows normais
      setRows((prevRows) =>
        prevRows.map((row) => ({
          ...row,
          components: row.components.map((gridItem: any) => ({
            ...gridItem,
            components: gridItem.components.map((component: Component) =>
              component?.id === id ? { ...component, ...updates } : component
            ),
          })),
        }))
      );
    }

    // Atualizar o componente selecionado, se for o mesmo que está sendo atualizado
    if (selectedComponent?.id === id) {
      setSelectedComponent((prev: any) => (prev ? { ...prev, ...updates } : null));
    }

    // Atualizar o layout no store global apenas se não for da área específica
    if (!isDepoimentoInSpecialArea) {
      const updatedLayout = convertRowsToLayout(rows);
      setLayout(updatedLayout);
    }
  };

  const handleComponentClick = (component: Component) => {
    setSelectedComponent(component)
    setIsDrawerOpen(true)
  }

  const handleDeleteComponent = (componentId: string) => {
    setRows((prevRows) =>
      prevRows.map((row) => ({
        ...row,
        components: row.components.map((gridItem: any) => ({
          ...gridItem,
          components: gridItem.components.filter(
            (component: Component) => component?.id !== componentId
          ),
        })),
      }))
    );
    setHoveredComponentId(null);
  };

  const handleEditComponent = (component: Component) => {
    setSelectedComponent(component);
    setIsDrawerOpen(true);
  };


  const handleDropComponent = (rowId: string, gridItemIndex: number, componentType: string) => {

    // Verificar se é um depoimento sendo inserido no dropzone geral
    if (componentType === 'depoimento-input') {
      toast({
        title: 'Alerta!',
        description: 'Depoimentos não podem ser adicionados aqui',
        status: 'warning',
        duration: 5000,
        isClosable: true,
      });
      return;
    }

    const types = [
      'text-input', 'image-input', 'vantagem-input',
      'selo-input', 'header-input', 'lista-input',
      'contador-input'
    ];
    const datas = [textData, imageData, vantagemData, seloData, headerData, listaData, contadorData];

    if (componentType.includes('input')) {
      const index = types.indexOf(componentType);
      if (index === -1) {
        return;
      }

      const newComponent = { ...datas[index], id: uuidv4() };

      setBackground('transparent');
      setRows((prevRows) => {
        const updatedRows = prevRows.map((row) =>
          row.id === rowId
            ? {
              ...row,
              components: row.components.map((gridItem: any, index: number) =>
                index === gridItemIndex
                  ? {
                    ...gridItem,
                    components: [newComponent] // Substituir componentes existentes por apenas o novo componente
                  }
                  : gridItem
              )
            }
            : row
        );

        // Atualizar o template no store com os dados atualizados
        const updatedLayout = convertRowsToLayout(updatedRows);
        setLayout(updatedLayout);

        return updatedRows;
      });

      setSelectedComponent(newComponent);
      setIsDrawerOpen(true);
    }
  };

  const updateRowLayout = (rowId: string, newLayout: string) => {
    let columnCount = 1;

    switch (newLayout) {
      case '1fr':
        columnCount = 1;
        break;
      case '9fr 3fr':
      case '1fr 2fr':
        columnCount = 2;
        break;
      case 'repeat(3, 1fr)':
        columnCount = 3;
        break;
      default:
        columnCount = 1;
    }

    setRows((prevRows) =>
      prevRows.map((row) =>
        row.id === rowId
          ? {
            ...row,
            layout: newLayout,
            components: Array.from({ length: columnCount }).map(
              (_, index) => row.components[index] || { components: [] }
            )
          }
          : row
      )
    );
    setIsEditDrawerOpen(false);
  };

  return (
    <HStack
      justifyContent={'center'}
      w={'100%'}
      bg="black.800"
      p={4}
    >

      <GridItem
        area="main"
        p={4}
        px={device === 'desktop' ? { base: 2, desktop: 8 } : 0}
        overflowY="auto"
        borderRadius={10}
        bg={template?.bg || 'gray.800'}
        w={device === 'desktop' ? '100%' : '480px'}
        transition={'width ease 0.3s'}
      >
        {/* Preview do layout quando arrastando - agora renderizado inline com cada linha */}

        {/* Sistema de rows drag and drop */}
        <DndContext
          collisionDetection={closestCenter}
          onDragStart={handleDragStart}
          onDragEnd={handleDragEnd}
          modifiers={[restrictToVerticalAxis]}
        >
          <SortableContext
            items={rows?.map(row => row.id) || []}
            strategy={verticalListSortingStrategy}
          >
            {/* Área de drop no início da lista */}
            {(currentDraggedLayout || draggedLayoutType) && (
              <Box
                minH="40px"
                border={(currentDraggedLayout || draggedLayoutType) ? "2px dashed" : "2px dashed transparent"}
                borderColor={(currentDraggedLayout || draggedLayoutType) ? "blue.400" : "transparent"}
                borderRadius="md"
                bg={(currentDraggedLayout || draggedLayoutType) ? "blue.50" : "transparent"}
                transition="all 0.2s"
                display="flex"
                alignItems="center"
                justifyContent="center"
                mb={2}
                onDragOver={(e) => {
                  e.preventDefault();
                  if (currentDraggedLayout || draggedLayoutType) {
                    setDragOverPosition({ rowId: 'start', position: 'above' });
                    if (currentDraggedLayout || draggedLayoutType) {
                      const layoutType = currentDraggedLayout || draggedLayoutType;
                      setDraggedLayoutType(layoutType);
                      setShowLayoutPreview(true);
                    }
                  }
                }}
                onDrop={(e) => {
                  e.preventDefault();
                  const draggedData = e.dataTransfer.getData('text/plain');

                  if (draggedData && draggedData.startsWith('layout-')) {
                    const layoutNumber = draggedData.split('-')[1];
                    let layout = '1fr';

                    switch (layoutNumber) {
                      case '1':
                        layout = '1fr';
                        break;
                      case '2':
                        layout = '2fr 1fr';
                        break;
                      case '3':
                        layout = '1fr 2fr';
                        break;
                      case '4':
                        layout = 'repeat(3, 1fr)';
                        break;
                    }

                    const columnCount = layoutNumber === '1' ? 1 : layoutNumber === '4' ? 3 : 2;
                    const gridColumns = [];
                    for (let i = 0; i < columnCount; i++) {
                      gridColumns.push({ components: [] });
                    }
                    const newRow = {
                      id: uuidv4(),
                      layout: layout,
                      components: gridColumns
                    };

                    const newRows = [newRow, ...rows];
                    setRows(newRows);
                    const updatedLayout = convertRowsToLayout(newRows);
                    setLayout(updatedLayout);

                    // Reset drag states
                    setDragOverPosition(null);
                    setShowLayoutPreview(false);
                    setDraggedLayoutType(null);
                    setCurrentDraggedLayout(null);
                    setIsDraggingComponent(false);
                  }
                }}>
                {(currentDraggedLayout || draggedLayoutType) && (
                  <Text color="blue.500" fontSize="sm" py={10}>
                    Solte aqui para adicionar no início
                  </Text>
                )}
              </Box>
            )}

            {rows?.map((row, rowIndex) => {
              let rowListeners: any = null
              return (
                <React.Fragment key={row.id}>
                  <SortableRow
                    id={row.id}
                    dragOverPosition={dragOverPosition}
                    activeRowId={activeRowId}
                    draggedLayoutType={draggedLayoutType}
                    isDraggingComponent={isDraggingComponent}
                    onListenersReady={(listeners) => { rowListeners = listeners }}
                  >
                    <Box
                      position="relative"
                      onMouseEnter={() => setHoveredRowId(row.id)}
                      onMouseLeave={() => setHoveredRowId(null)}
                      onDragOver={(e) => handleRowDragOver(e, row.id)}
                      onDragLeave={handleRowDragLeave}
                      onDrop={(e) => handleRowDrop(e, row.id)}
                      border={hoveredRowId === row.id ? "1px dashed gray" : "1px solid transparent"}
                      borderRadius="md"
                      transition="border 0.2s ease"
                      opacity={activeRowId === row.id ? 0.5 : 1}
                    >
                      {/* Renderizar a Grid com base no layout */}
                      <Grid
                        templateColumns={ device === 'mobile' && (row?.layout ?? '')?.includes?.('2') || device === 'mobile' && (row?.layout ?? '')?.includes?.('3') ? '1fr' : row?.layout || '1fr'}
                        gap={0}
                        p={1}
                        px={0}
                        bg={background}
                        borderRadius="lg"
                        mb={0}
                        minH={'80px'}
                        onDrop={handleDrop}
                        onDragOver={handleDragOver}
                        onDragLeave={handleDragLeave}
                        onClick={() => setSelectedRowId(row.id)}
                        cursor="pointer"
                        position={'relative'}
                      >
                        {Array.from({ length: row.components?.length || 1 }).map((_, gridItemIndex) => (
                          <GridItem
                            key={gridItemIndex}
                            p={4}
                            borderRadius="md"
                            border={draggedOverGridItem?.rowId === row.id && draggedOverGridItem?.gridIndex === gridItemIndex && isDraggingComponent ? "2px dashed" : "2px dashed transparent"}
                            borderColor={draggedOverGridItem?.rowId === row.id && draggedOverGridItem?.gridIndex === gridItemIndex && isDraggingComponent ? "teal.400" : "transparent"}
                            bg={draggedOverGridItem?.rowId === row.id && draggedOverGridItem?.gridIndex === gridItemIndex && isDraggingComponent ? "teal.50" : "transparent"}
                            transition="all 0.2s"
                            onDrop={(e) => {
                              e.preventDefault();
                              e.stopPropagation();
                              const componentType = e.dataTransfer.getData('text/plain');
                              handleDropComponent(row.id, gridItemIndex, componentType);
                              setDraggedOverGridItem(null);
                              setIsDraggingComponent(false);
                            }}
                            onDragOver={(e) => {
                              e.preventDefault();
                              e.stopPropagation();
                              // Durante dragover, verificar se há dados sendo arrastados
                              const types = e.dataTransfer.types;
                              if (types.includes('text/plain')) {
                                setDraggedOverGridItem({ rowId: row.id, gridIndex: gridItemIndex });
                              }
                            }}
                            onDragLeave={(e) => {
                              e.preventDefault();
                              // Só remove o highlight se realmente saiu do GridItem
                              const rect = e.currentTarget.getBoundingClientRect();
                              const x = e.clientX;
                              const y = e.clientY;
                              if (x < rect.left || x > rect.right || y < rect.top || y > rect.bottom) {
                                setDraggedOverGridItem(null);
                              }
                            }}
                          >
                            {/* Renderizar componentes dentro do GridItem */}
                            {row.components[gridItemIndex]?.components?.length > 0 ? (
                              row.components[gridItemIndex].components.map((component: any) => (
                                <Box
                                  key={component?.id}
                                  position="relative"
                                  onMouseEnter={() => { setHoveredComponentId(component?.id); setHoveredRowId(null) }}
                                  onMouseLeave={() => { setHoveredComponentId(null); setHoveredRowId(row.id) }}
                                  border={hoveredComponentId === component?.id ? "1px dashed gray" : "1px solid transparent"}
                                  borderRadius="md"
                                  transition="border 0.2s ease"
                                >
                                  {component?.type === 'text' && (
                                    <TextComponent
                                      component={component}
                                      handleComponentClick={handleComponentClick}
                                    />
                                  )}
                                  {component?.type === 'image' && (
                                    <ImageComponent
                                      component={component}
                                      handleComponentClick={handleComponentClick}
                                    />
                                  )}
                                  {component?.type === 'vantagem' && (
                                    <VantagemComponent
                                      component={component}
                                      handleComponentClick={handleComponentClick}
                                    />
                                  )}
                                  {component?.type === 'selo' && (
                                    <SeloComponent
                                      component={component}
                                      handleComponentClick={handleComponentClick}
                                    />
                                  )}
                                  {component?.type === 'header' && (
                                    <HeaderComponent
                                      component={component}
                                      handleComponentClick={handleComponentClick}
                                    />
                                  )}
                                  {component?.type === 'lista' && (
                                    <ListaComponent
                                      component={component}
                                      handleComponentClick={handleComponentClick}
                                    />
                                  )}
                                  {component?.type === 'contador' && (
                                    <ContadorComponent
                                      component={component}
                                      handleComponentClick={handleComponentClick}
                                    />
                                  )}
                                  {component?.type === 'depoimento' && (
                                    <DepoimentoComponent
                                      component={component}
                                      handleComponentClick={handleComponentClick}
                                    />
                                  )}
                                  {hoveredComponentId === component?.id && (
                                    <Box
                                      position="absolute"
                                      top={0}
                                      right={0}
                                      p={2}
                                      bg="gray.700"
                                      color="white"
                                      borderRadius="md"
                                      zIndex={9999}
                                    >
                                      <ComponentOptionsComponent
                                        onDelete={() => handleDeleteComponent(component?.id)}
                                        onEdit={() => handleEditComponent(component)}
                                      />
                                    </Box>
                                  )}
                                </Box>
                              ))
                            ) : (
                              <Box
                                display="flex"
                                alignItems="center"
                                justifyContent="center"
                                minH="80px"
                                color={draggedOverGridItem?.rowId === row.id && draggedOverGridItem?.gridIndex === gridItemIndex && isDraggingComponent ? "teal.600" : "gray.500"}
                                fontWeight={draggedOverGridItem?.rowId === row.id && draggedOverGridItem?.gridIndex === gridItemIndex && isDraggingComponent ? "bold" : "normal"}
                              >
                                <Text>
                                  {draggedOverGridItem?.rowId === row.id && draggedOverGridItem?.gridIndex === gridItemIndex && isDraggingComponent
                                    ? "Solte o componente aqui"
                                    : "Arraste componentes aqui"
                                  }
                                </Text>
                              </Box>
                            )}

                          </GridItem>
                        ))}
                        {hoveredRowId === row.id && (
                          <Box
                            position="absolute"
                            top={0}
                            right={0}
                            p={2}
                            bg="gray.700"
                            color="white"
                            borderRadius="md"
                          >
                            <ComponentOptionsRow
                              id={row.id}
                              onDelete={() => handleDeleteRow({ id: row.id })}
                              onDuplicate={() => handleDuplicateRow({ id: row.id })}
                              onEdit={() => handleEditRow(row.id)}
                              dragListeners={rowListeners}
                            />
                          </Box>
                        )}
                      </Grid>
                    </Box>
                  </SortableRow>

                  {/* Área de drop entre as linhas */}
                  {rowIndex < rows.length - 1 && (currentDraggedLayout || draggedLayoutType) && (
                    <Box my={1}>
                      <Box
                        minH="30px"
                        border={(currentDraggedLayout || draggedLayoutType) ? "2px dashed" : "2px dashed transparent"}
                        borderColor={(currentDraggedLayout || draggedLayoutType) ? "blue.400" : "transparent"}
                        borderRadius="md"
                        bg={(currentDraggedLayout || draggedLayoutType) ? "blue.50" : "transparent"}
                        transition="all 0.2s"
                        display="flex"
                        alignItems="center"
                        justifyContent="center"
                        onDragOver={(e) => {
                          e.preventDefault();
                          if (currentDraggedLayout || draggedLayoutType) {
                            setDragOverPosition({ rowId: `between-${rowIndex}`, position: 'below' });
                            if (currentDraggedLayout || draggedLayoutType) {
                              const layoutType = currentDraggedLayout || draggedLayoutType;
                              setDraggedLayoutType(layoutType);
                              setShowLayoutPreview(true);
                            }
                          }
                        }}
                        onDrop={(e) => {
                          e.preventDefault();
                          const draggedData = e.dataTransfer.getData('text/plain');

                          if (draggedData && draggedData.startsWith('layout-')) {
                            const layoutNumber = draggedData.split('-')[1];
                            let layout = '1fr';

                            switch (layoutNumber) {
                              case '1':
                                layout = '1fr';
                                break;
                              case '2':
                                layout = '2fr 1fr';
                                break;
                              case '3':
                                layout = '1fr 2fr';
                                break;
                              case '4':
                                layout = 'repeat(3, 1fr)';
                                break;
                            }

                            const columnCount = layoutNumber === '1' ? 1 : layoutNumber === '4' ? 3 : 2;
                            const gridColumns = [];
                            for (let i = 0; i < columnCount; i++) {
                              gridColumns.push({ components: [] });
                            }
                            const newRow = {
                               id: uuidv4(),
                               layout: layout,
                               components: gridColumns
                             };

                             const newRows = [...rows];
                          newRows.splice(rowIndex + 1, 0, newRow);
                          setRows(newRows);
                          const updatedLayout = convertRowsToLayout(newRows);
                          setLayout(updatedLayout);

                          // Reset drag states
                          setDragOverPosition(null);
                          setShowLayoutPreview(false);
                          setDraggedLayoutType(null);
                          setCurrentDraggedLayout(null);
                          setIsDraggingComponent(false);
                        }
                      }}
                    >
                      {isDraggingComponent && (
                          <Text color="blue.500" fontSize="xs" py={10}>
                            Inserir aqui
                          </Text>
                        )}
                      </Box>
                      
                      {/* Preview do layout quando arrastando */}
                      {showLayoutPreview && draggedLayoutType && (
                        <Box
                          mt={2}
                          p={3}
                          bg="blue.100"
                          borderRadius="md"
                          border="1px solid"
                          borderColor="blue.300"
                        >
                          <Text fontSize="xs" color="blue.600" mb={2} fontWeight="medium">
                            Preview do Layout:
                          </Text>
                          <Grid
                            templateColumns={(() => {
                              const layoutNumber = draggedLayoutType.replace('layout-', '');
                              switch (layoutNumber) {
                                case '1': return '1fr';
                                case '2': return '2fr 1fr';
                                case '3': return '1fr 2fr';
                                case '4': return 'repeat(3, 1fr)';
                                default: return '1fr';
                              }
                            })()}
                            gap={2}
                            minH="60px"
                          >
                            {Array.from({ length: (() => {
                              const layoutNumber = draggedLayoutType.replace('layout-', '');
                              return layoutNumber === '4' ? 3 : layoutNumber === '1' ? 1 : 2;
                            })() }).map((_, index) => (
                              <GridItem
                                key={index}
                                bg="white"
                                border="1px dashed"
                                borderColor="blue.400"
                                borderRadius="md"
                                display="flex"
                                alignItems="center"
                                justifyContent="center"
                                minH="50px"
                              >
                                <Text fontSize="xs" color="gray.500">
                                  Coluna {index + 1}
                                </Text>
                              </GridItem>
                            ))}
                          </Grid>
                        </Box>
                      )}
                    </Box>
                  )}
                </React.Fragment>
              )
            })}
          </SortableContext>

          {/* Área de drop no final da lista */}
          {(currentDraggedLayout || draggedLayoutType) && (
            <Box
              minH="100px"
              border={(currentDraggedLayout || draggedLayoutType) ? "2px dashed" : "2px dashed transparent"}
              borderColor={(currentDraggedLayout || draggedLayoutType) ? "blue.400" : "transparent"}
              borderRadius="md"
              bg={(currentDraggedLayout || draggedLayoutType) ? "blue.50" : "transparent"}
              transition="all 0.2s"
              display="flex"
              alignItems="center"
              justifyContent="center"
              onDragOver={(e) => {
                e.preventDefault();
                if (currentDraggedLayout || draggedLayoutType) {
                  setDragOverPosition({ rowId: 'end', position: 'below' });
                  if (currentDraggedLayout || draggedLayoutType) {
                    const layoutType = currentDraggedLayout || draggedLayoutType;
                    setDraggedLayoutType(layoutType);
                    setShowLayoutPreview(true);
                  }
                }
              }}
              onDrop={(e) => {
                e.preventDefault();
                const draggedData = e.dataTransfer.getData('text/plain');

                if (draggedData && draggedData.startsWith('layout-')) {
                  const layoutNumber = draggedData.split('-')[1];
                  let layout = '1fr';

                  switch (layoutNumber) {
                    case '1':
                      layout = '1fr';
                      break;
                    case '2':
                      layout = '2fr 1fr';
                      break;
                    case '3':
                      layout = '1fr 2fr';
                      break;
                    case '4':
                      layout = 'repeat(3, 1fr)';
                      break;
                  }

                  const columnCount = layoutNumber === '1' ? 1 : layoutNumber === '4' ? 3 : 2;
                  const gridColumns = [];
                  for (let i = 0; i < columnCount; i++) {
                    gridColumns.push({ components: [] });
                  }
                  const newRow = {
                    id: uuidv4(),
                    layout: layout,
                    components: gridColumns
                  };

                  const newRows = [...rows, newRow];
                  setRows(newRows);
                  const updatedLayout = convertRowsToLayout(newRows);
                  setLayout(updatedLayout);

                  // Reset drag states
                  setDragOverPosition(null);
                  setShowLayoutPreview(false);
                  setDraggedLayoutType(null);
                  setCurrentDraggedLayout(null);
                  setIsDraggingComponent(false);
                }
              }}>
              {(currentDraggedLayout || draggedLayoutType) && (
                <VStack spacing={3}>
                  <Text color="blue.500" fontSize="sm">
                    Solte aqui para adicionar no final
                  </Text>
                  
                  {/* Preview do layout quando arrastando */}
                  {showLayoutPreview && draggedLayoutType && (
                    <Box
                      p={3}
                      bg="blue.100"
                      borderRadius="md"
                      border="1px solid"
                      borderColor="blue.300"
                      w="full"
                      maxW="400px"
                    >
                      <Text fontSize="xs" color="blue.600" mb={2} fontWeight="medium">
                        Preview do Layout:
                      </Text>
                      <Grid
                        templateColumns={(() => {
                          const layoutNumber = draggedLayoutType.replace('layout-', '');
                          switch (layoutNumber) {
                            case '1': return '1fr';
                            case '2': return '2fr 1fr';
                            case '3': return '1fr 2fr';
                            case '4': return 'repeat(3, 1fr)';
                            default: return '1fr';
                          }
                        })()}
                        gap={2}
                        minH="60px"
                      >
                        {Array.from({ length: (() => {
                          const layoutNumber = draggedLayoutType.replace('layout-', '');
                          return layoutNumber === '4' ? 3 : layoutNumber === '1' ? 1 : 2;
                        })() }).map((_, index) => (
                          <GridItem
                            key={index}
                            bg="white"
                            border="1px dashed"
                            borderColor="blue.400"
                            borderRadius="md"
                            display="flex"
                            alignItems="center"
                            justifyContent="center"
                            minH="50px"
                          >
                            <Text fontSize="xs" color="gray.500">
                              Coluna {index + 1}
                            </Text>
                          </GridItem>
                        ))}
                      </Grid>
                    </Box>
                  )}
                </VStack>
              )}
            </Box>
          )}

          <DragOverlay>
            {activeRowId ? (
              <Box
                bg={template?.bg || 'gray.800'}
                borderRadius="lg"
                border="2px solid"
                borderColor="blue.400"
                opacity={0.9}
                transform="rotate(3deg)"
                boxShadow="xl"
                minH="80px"
                p={1}
              >
                {(() => {
                  const draggedRow = rows?.find(row => row.id === activeRowId)
                  if (!draggedRow) return null

                  return (
                    <Grid
                      templateColumns={draggedRow.layout || '1fr'}
                      gap={0}
                      p={1}
                      bg="transparent"
                      borderRadius="lg"
                      minH="80px"
                    >
                      {Array.from({ length: draggedRow.components?.length || 1 }).map((_, gridItemIndex) => (
                        <GridItem
                          key={gridItemIndex}
                          p={4}
                          borderRadius="md"
                          bg="blue.50"
                          border="1px dashed"
                          borderColor="blue.300"
                          display="flex"
                          alignItems="center"
                          justifyContent="center"
                          minH="70px"
                        >
                          {draggedRow.components[gridItemIndex]?.components?.length > 0 ? (
                            <Text fontSize="sm" color="blue.600" fontWeight="medium">
                              {draggedRow.components[gridItemIndex].components.length} componente(s)
                            </Text>
                          ) : (
                            <Text fontSize="sm" color="gray.500">
                              Vazio
                            </Text>
                          )}
                        </GridItem>
                      ))}
                    </Grid>
                  )
                })()}
              </Box>
            ) : null}
          </DragOverlay>
        </DndContext>
        <Drawers
          {...{
            background,
            handleDelete,
            handleDuplicate,
            isDrawerOpen,
            selectedComponent,
            setBackground,
            setIsDrawerOpen,
            updateComponent
          }}
        />

        {/* Drawer para editar o layout da linha */}
        <LinhaDrawer
          editingRowId={editingRowId}
          updateRowLayout={updateRowLayout}
          onDelete={() => handleDeleteRow({ id: editingRowId as string })}
          onDuplicate={() => handleDuplicateRow({ id: editingRowId as string })}
          onClose={() => setIsEditDrawerOpen(false)}
          isOpen={isEditDrawerOpen}
        />

        <Box
          width={device === 'mobile' ? '480px' : '100%'}
          mx="auto"
          minH="100vh"
        >
          <Grid
            templateColumns={device === 'mobile' ? '1fr' : { base: '1fr', md: '1fr 480px' }}
            gap={4}
            p={4}
          >
            {/* Coluna principal: Produto + Formulário + Pagamento */}
            <GridItem
              borderRadius={'lg'}
              bg={template?.bg_form_payment || 'white'}
            >
              {/* Produto */}
              <Box p={6} borderRadius="lg" mb={0}>
                <HStack spacing={4} align="flex-start">
                  <ChakraImage
                    bg={'white'}
                    src={Helper.storageUrl(produto?.image) || "/product-placeholder.svg"}
                    alt="Produto"
                    boxSize="60px"
                    borderRadius={'lg'}
                  />
                  <Box>
                    <Text fontWeight="bold" color={template.text_primary}>
                      {produto?.name || "Carregando..."}
                    </Text>
                    <Text color={template.text_active}>
                      1 X de {checkout ? `${Helper.formatPrice(produto?.price as number)}` : "R$ 0,00"}
                    </Text>
                    <Text fontSize="sm" color={template.text_secondary}>
                      ou {checkout ? `${Helper.formatPrice(produto?.price as number)}` : "R$ 0,00"} à vista
                    </Text>
                  </Box>
                </HStack>
              </Box>

              {/* Formulário */}
              <UserDataForm
                formData={formData}
                onFormChange={handleFormChange}
              />

              {/* Pagamento */}
              <PaymentForm
                metodo={metodo}
                onMetodoChange={handleMetodoChange}
                cardData={cardData}
                onCardDataChange={handleCardDataChange}
              />
            </GridItem>


            {/* Resumo do Pedido */}
            <GridItem>
              <Box position="sticky" top={8}>
                <Box
                  bg={template.bg_form_payment}
                  p={6}
                  borderRadius="lg">


                  <HStack spacing={4} mb={6} align="flex-start" position={'relative'}>
                    <Text pos="absolute" top={0} left={0} right={0} h={'45px'} textAlign={'center'} pt={2} borderTopRadius={'lg'} fontSize="xl" fontWeight="bold" mb={6} m={-6} bg={'green.500'} color="white">
                      Compra segura
                    </Text>
                    <ChakraImage
                      bg={'white'}
                      borderRadius={'lg'}
                      mt={10}
                      src={Helper.storageUrl(produto?.image) || "/product-placeholder.svg"}
                      alt={produto?.name || "Produto"}
                      boxSize="60px"
                    />
                    <Box flex={1} mt={10}>
                      <Text fontWeight="bold" color={template.text_primary}>
                        {produto?.name || "Carregando..."}
                      </Text>
                      <Text fontSize="sm" color={template.text_secondary}>Precisa de ajuda?</Text>
                      <Link fontSize="sm" color={template.text_secondary} href="#">
                        Veja o contato do vendedor
                      </Link>
                    </Box>
                  </HStack>

                  <Box py={6} borderY="1px" borderColor="gray.200">
                    <Text fontWeight="bold" mb={2}>Total</Text>
                    <Text fontSize="xl" fontWeight="bold" color={template.text_active} mb={1}>
                      1 X de {produto ? `${Helper.formatPrice(produto?.price)}` : "R$ 0,00"}
                    </Text>
                    <Text fontSize="sm" color={template.text_secondary}>
                      ou {produto ? `${Helper.formatPrice(produto?.price)}` : "R$ 0,00"} à vista
                    </Text>
                    <Text fontSize="sm" color={template.text_secondary} mt={2}>Renovação atual</Text>
                  </Box>

                  <Box mt={6}>
                    {Helper.storageUrl(setting?.favicon_light) && (
                    <ChakraImage src={Helper.storageUrl(setting?.favicon_light)} alt={setting.software_name} height="20px" mb={4} />
                  )}
                    <Text fontSize="xs" color={template.text_secondary} mb={2}>
                      {setting.software_name} é uma instituição de pagamento para o comércio eletrônico regulada pelo Banco Central do Brasil e protegida pela
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
              </Box>

              <VStack
                w={'100%'}
                mt={4}
                borderRadius="lg">

                {/* Área de dropzone específica para depoimentos */}
                <Box
                  w={'100%'}
                  minH={'120px'}
                  border={isDraggingComponent ? "2px dashed" : "2px solid transparent"}
                  borderColor={isDraggingComponent ? "blue.400" : "transparent"}
                  borderRadius="md"
                  bg={isDraggingComponent ? "blue.50" : "transparent"}
                  transition="all 0.2s"
                  onDragOver={handleDepoimentoDragOver}
                  onDrop={handleDepoimentoDrop}
                >
                  {(depoimentos || []).length === 0 ? (
                    <Box
                      w={'100%'}
                      minH={'80px'}
                      display="flex"
                      alignItems="center"
                      justifyContent="center"
                      color={template.text_secondary || "gray.500"}
                      fontSize="sm"
                bg={template.bg_form_payment}
                p={6}
                    >
                      <Text>
                        {isDraggingComponent ? "Solte o depoimento aqui" : "Arraste depoimentos para esta área"}
                      </Text>
                    </Box>
                  ) : (
                    <VStack spacing={4} w={'100%'}>
                      {(() => {
                        return (depoimentos || []).map((depoimento) => (
                        <Box
                          key={depoimento.id}
                          w={'100%'}
                          position="relative"
                          onMouseEnter={() => setHoveredComponentId(depoimento?.id)}
                          onMouseLeave={() => setHoveredComponentId(null)}
                        >
                          <DepoimentoComponent
                            component={depoimento}
                            handleComponentClick={handleDepoimentoClick}
                          />
                          {hoveredComponentId === depoimento?.id && (
                            <Box
                              position="absolute"
                              top={0}
                              right={0}
                              zIndex={10}
                            >
                              <ComponentOptionsComponent
                                onEdit={() => handleDepoimentoClick(depoimento)}
                                onDelete={() => handleDeleteDepoimento(depoimento.id)}
                              />
                            </Box>
                          )}
                        </Box>
                      ));
                      })()}
                    </VStack>
                  )}
                </Box>
              </VStack>
            </GridItem>
          </Grid>
        </Box>
      </GridItem>
    </HStack>
  )
}