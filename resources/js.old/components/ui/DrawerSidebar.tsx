import { Box, Button, Grid, HStack, Text, VStack, Select, Input, Center, useBreakpointValue } from '@chakra-ui/react'
import { Type, Image as ImageIcon, CheckCircle, ListTodo, Facebook, MapPin, LucideTimer, LucideMessageSquarePlus, LucideLayoutPanelTop, LucideMedal, GripHorizontal } from 'lucide-react'
import { useState, useRef } from 'react'
import { ColorPicker } from './ColorPicker';
import useConfig from '../../stores/config';

interface DrawerSidebarProps {
    onDragStart?: (layoutType?: string) => void;
    onDragEnd?: () => void;
}

export function DrawerSidebar({ onDragStart, onDragEnd }: DrawerSidebarProps = {}) {
    const [activeSection, setActiveSection] = useState('components');
    const [drawerHeight, setDrawerHeight] = useState(400);
    const [isResizing, setIsResizing] = useState(false);
    const resizeRef = useRef<HTMLDivElement>(null);


  const { template, setTemplate, config } = useConfig();


  const handleResizeStart = (e: React.MouseEvent) => {
        setIsResizing(true);
        e.preventDefault();
        e.stopPropagation();
        
        const startY = e.clientY;
        const startHeight = drawerHeight;
        
        const handleMouseMove = (e: MouseEvent) => {
            e.preventDefault();
            const deltaY = startY - e.clientY;
            const newHeight = startHeight + deltaY;
            const minHeight = 200;
            const maxHeight = window.innerHeight * 0.8;
            
            const clampedHeight = Math.max(minHeight, Math.min(maxHeight, newHeight));
            setDrawerHeight(clampedHeight);
        };
        
        const handleMouseUp = () => {
            setIsResizing(false);
            document.removeEventListener('mousemove', handleMouseMove);
            document.removeEventListener('mouseup', handleMouseUp);
        };
        
        document.addEventListener('mousemove', handleMouseMove);
        document.addEventListener('mouseup', handleMouseUp);
    };

    const handleTouchStart = (e: React.TouchEvent) => {
        // Verificar se o toque é especificamente no resize handle
        const target = e.target as HTMLElement;
        const resizeHandle = resizeRef.current;
        if (!resizeHandle || (!resizeHandle.contains(target) && target !== resizeHandle)) {
            return;
        }
        
        setIsResizing(true);
        e.preventDefault();
        e.stopPropagation();
        
        const startY = e.touches[0].clientY;
        const startHeight = drawerHeight;
        
        const handleTouchMove = (e: TouchEvent) => {
            e.preventDefault();
            const deltaY = startY - e.touches[0].clientY;
            const newHeight = startHeight + deltaY;
            const minHeight = 200;
            const maxHeight = window.innerHeight * 0.8;
            
            const clampedHeight = Math.max(minHeight, Math.min(maxHeight, newHeight));
            setDrawerHeight(clampedHeight);
        };
        
        const handleTouchEnd = () => {
            setIsResizing(false);
            document.removeEventListener('touchmove', handleTouchMove);
            document.removeEventListener('touchend', handleTouchEnd);
        };
        
        document.addEventListener('touchmove', handleTouchMove, { passive: false });
        document.addEventListener('touchend', handleTouchEnd);
    };

  const handleDragStart = (e: React.DragEvent, componentType: string) => {
    e.dataTransfer.setData('text/plain', componentType);
    e.dataTransfer.effectAllowed = 'copy';
    // Adicionar um delay para garantir que o dataTransfer seja configurado
    setTimeout(() => {
      onDragStart?.();
    }, 0);
  };

  const handleDragEnd = () => {
    onDragEnd?.();
  };

  const ComponentsSection = () => (
    <VStack align="stretch" spacing={4}>
      <Grid templateColumns="repeat(2, 1fr)" gap={2}>
        <Button
          height="100px"
          bg="transparent"
          borderRadius={10}
          borderWidth={2}
          borderColor={'gray.700'}
          _hover={{
            bg: 'transparent',
            borderColor: 'teal'
          }}
          color="white"
          fontSize="sm"
          display="flex"
          flexDir="column"
          gap={2}
          draggable
          onDragStart={(e) => handleDragStart(e, 'text-input')}
          onDragEnd={handleDragEnd}
        >
          <Type size={24} />
          Texto
        </Button>

        <Button
          height="100px"
          bg="transparent"
          borderRadius={10}
          borderWidth={2}
          borderColor={'gray.700'}
          _hover={{
            bg: 'transparent',
            borderColor: 'teal'
          }}
          color="white"
          fontSize="sm"
          display="flex"
          flexDir="column"
          gap={2}

          draggable
          onDragStart={(e) => handleDragStart(e, 'image-input')}
          onDragEnd={handleDragEnd}
        >
          <ImageIcon size={24} />
          Imagem
        </Button>

        <Button
          height="100px"
          bg="transparent"
          borderRadius={10}
          borderWidth={2}
          borderColor={'gray.700'}
          _hover={{
            bg: 'transparent',
            borderColor: 'teal'
          }}
          color="white"
          fontSize="sm"
          display="flex"
          flexDir="column"
          gap={2}

          draggable
          onDragStart={(e) => handleDragStart(e, 'vantagem-input')}
          onDragEnd={handleDragEnd}
        >
          <CheckCircle size={24} />
          Vantagens
        </Button>

        <Button
          height="100px"
          bg="transparent"
          borderRadius={10}
          borderWidth={2}
          borderColor={'gray.700'}
          _hover={{
            bg: 'transparent',
            borderColor: 'teal'
          }}
          color="white"
          fontSize="sm"
          display="flex"
          flexDir="column"
          gap={2}
          draggable
          onDragStart={(e) => handleDragStart(e, 'selo-input')}
          onDragEnd={handleDragEnd}
        >
          <LucideMedal size={24} />
          Selo
        </Button>

        <Button
          height="100px"
          bg="transparent"
          borderRadius={10}
          borderWidth={2}
          borderColor={'gray.700'}
          _hover={{
            bg: 'transparent',
            borderColor: 'teal'
          }}
          color="white"
          fontSize="sm"
          display="flex"
          flexDir="column"
          gap={2}

          draggable
          onDragStart={(e) => handleDragStart(e, 'header-input')}
          onDragEnd={handleDragEnd}
        >
          <LucideLayoutPanelTop size={24} />
          Header
        </Button>

        <Button
          height="100px"
          bg="transparent"
          borderRadius={10}
          borderWidth={2}
          borderColor={'gray.700'}
          _hover={{
            bg: 'transparent',
            borderColor: 'teal'
          }}
          color="white"
          fontSize="sm"
          display="flex"
          flexDir="column"
          gap={2}

          draggable
          onDragStart={(e) => handleDragStart(e, 'lista-input')}
          onDragEnd={handleDragEnd}
        >
          <ListTodo size={24} />
          Lista
        </Button>

        <Button
          height="100px"
          bg="transparent"
          borderRadius={10}
          borderWidth={2}
          borderColor={'gray.700'}
          _hover={{
            bg: 'transparent',
            borderColor: 'teal'
          }}
          color="white"
          fontSize="sm"
          display="flex"
          flexDir="column"
          gap={2}
          draggable
          onDragStart={(e) => handleDragStart(e, 'contador-input')}
          onDragEnd={handleDragEnd}
        >
          <LucideTimer size={24} />
          Cronômetro
        </Button>

        <Button
          height="100px"
          bg="transparent"
          borderRadius={10}
          borderWidth={2}
          borderColor={'gray.700'}
          _hover={{
            bg: 'transparent',
            borderColor: 'teal'
          }}
          color="white"
          fontSize="sm"
          display="flex"
          flexDir="column"
          gap={2}
          draggable
          onDragStart={(e) => handleDragStart(e, 'depoimento-input')}
          onDragEnd={handleDragEnd}

        >
          <LucideMessageSquarePlus size={24} />
          Depoimento
        </Button>
      </Grid>
    </VStack>
  )

  const LayoutsSection = () => (
    <VStack align="stretch" spacing={4}>
      <Grid templateRows="repeat(4, 1fr)" gap={4}>
        <Grid
          templateColumns="1fr"
          gap={4}
          borderWidth={1}
          borderColor={'gray.500'}
          borderRadius="lg"
          p={2}
          _hover={{ bg: 'gray.600' }}
          cursor="pointer"
          draggable
          onDragStart={(e) => {
            e.dataTransfer.setData('text/plain', 'layout-1');
            e.dataTransfer.effectAllowed = 'copy';
            setTimeout(() => {
              onDragStart?.('layout-1');
            }, 0);
          }}
          onDragEnd={handleDragEnd}
        >
          <Center height="120px" bg="gray.700" borderRadius="lg" color="white">
            1
          </Center>
        </Grid>

        <Grid
          templateColumns="2fr 1fr"
          gap={4}
          borderWidth={1}
          borderColor={'gray.500'}
          borderRadius="lg"
          p={2}
          _hover={{ bg: 'gray.600' }}
          cursor="pointer"
          draggable
          onDragStart={(e) => {
            e.dataTransfer.setData('text/plain', 'layout-2');
            e.dataTransfer.effectAllowed = 'copy';
            setTimeout(() => {
              onDragStart?.('layout-2');
            }, 0);
          }}
          onDragEnd={handleDragEnd}
        >
          <Center height="120px" bg="gray.700" borderRadius="lg" color="white">
            1
          </Center>
          <Center height="120px" bg="gray.700" borderRadius="lg" color="white">
            2
          </Center>
        </Grid>

        <Grid
          templateColumns="1fr 2fr"
          gap={4}
          borderWidth={1}
          borderColor={'gray.500'}
          borderRadius="lg"
          p={2}
          _hover={{ bg: 'gray.600' }}
          cursor="pointer"
          draggable
          onDragStart={(e) => {
            e.dataTransfer.setData('text/plain', 'layout-3');
            e.dataTransfer.effectAllowed = 'copy';
            setTimeout(() => {
              onDragStart?.('layout-3');
            }, 0);
          }}
          onDragEnd={handleDragEnd}
        >
          <Center height="120px" bg="gray.700" borderRadius="lg" color="white">
            1
          </Center>
          <Center height="120px" bg="gray.700" borderRadius="lg" color="white">
            2
          </Center>
        </Grid>

        <Grid
          templateColumns="repeat(3, 1fr)"
          gap={4}
          borderWidth={1}
          borderColor={'gray.500'}
          borderRadius="lg"
          p={2}
          _hover={{ bg: 'gray.600' }}
          cursor="pointer"
          draggable
          onDragStart={(e) => {
            e.dataTransfer.setData('text/plain', 'layout-4');
            e.dataTransfer.effectAllowed = 'copy';
            setTimeout(() => {
              onDragStart?.('layout-4');
            }, 0);
          }}
          onDragEnd={handleDragEnd}
        >
          <Center height="120px" bg="gray.700" borderRadius="lg" color="white">
            1
          </Center>
          <Center height="120px" bg="gray.700" borderRadius="lg" color="white">
            2
          </Center>
          <Center height="120px" bg="gray.700" borderRadius="lg" color="white">
            3
          </Center>
        </Grid>
      </Grid>
    </VStack>
  )

  const SettingsSection = () => (
    <VStack align="stretch" spacing={4} overflowY={'auto'}>
      
      <Box>
        <ColorPicker
          title="Cor primária do texto"
          value={template.text_primary || '#000000'}
          onChange={(value) => setTemplate({ ...template, text_primary: value })}
        />
      </Box>

      <Box>
        <ColorPicker
          title="Cor secundária do texto"
          value={template.text_secondary || '#666666'}
          onChange={(value) => setTemplate({ ...template, text_secondary: value })}
        />
      </Box>

      <Box>
        <ColorPicker
          title="Cor ativa do texto"
          value={template.text_active || '#0b6856'}
          onChange={(value) => setTemplate({ ...template, text_active: value })}
        />
      </Box>

      <Box>
        <ColorPicker
          title="Cor dos ícones"
          value={template.icon_color || '#000000'}
          onChange={(value) => setTemplate({ ...template, icon_color: value })}
        />
      </Box>

      <Box>
        <ColorPicker
          title="Cor de fundo"
          value={template.bg || '#f1f1f1'}
          onChange={(value) => setTemplate({ ...template, bg: value })}
        />
      </Box>

      <Box>
        <ColorPicker
          title="Cor de fundo do formulário de pagamento"
          value={template.bg_form_payment || '#ffffff'}
          onChange={(value) => setTemplate({ ...template, bg_form_payment: value })}
        />
      </Box>

      <Box
        borderWidth={1}
        borderColor={'gray.500'}
        borderRadius="lg"
        p={2}
        px={4}
      >
        <Text color="gray.400" fontSize="sm" fontWeight="bold" my={1} mb={2}>
          Botões não selecionados
        </Text>

        <VStack align="stretch" spacing={2}>
          <Box>
            <ColorPicker
              title="Cor do texto"
              value={template.btn_unselected_text_color || '#666666'}
          onChange={(value) => setTemplate({ ...template, btn_unselected_text_color: value })}
            />
          </Box>

          <Box>
            <ColorPicker
              title="Cor de fundo"
              value={template.btn_unselected_bg_color || '#f1f1f1'}
          onChange={(value) => setTemplate({ ...template, btn_unselected_bg_color: value })}
            />
          </Box>

          <Box>
            <ColorPicker
              title="Cor dos ícones"
              value={template.btn_unselected_icon_color || '#000000'}
          onChange={(value) => setTemplate({ ...template, btn_unselected_icon_color: value })}
            />
          </Box>
        </VStack>
      </Box>

      <Box
        borderWidth={1}
        borderColor={'gray.500'}
        borderRadius="lg"
        p={2}
        px={4}
      >
        <Text color="gray.400" fontSize="sm" fontWeight="bold" my={1} mb={2}>
          Botão selecionado
        </Text>

        <VStack align="stretch" spacing={2}>
          <Box>
            <ColorPicker
              title="Cor do texto"
              value={template.btn_selected_text_color || '#ffffff'}
          onChange={(value) => setTemplate({ ...template, btn_selected_text_color: value })}
            />
          </Box>
          <Box>
            <ColorPicker
              title="Cor de fundo"
              value={template.btn_selected_bg_color || '#0b6856'}
          onChange={(value) => setTemplate({ ...template, btn_selected_bg_color: value })}
            />
          </Box>
          <Box>
            <ColorPicker
              title="Cor dos ícones"
              value={template.btn_selected_icon_color || '#ffffff'}
          onChange={(value) => setTemplate({ ...template, btn_selected_icon_color: value })}
            />
          </Box>
        </VStack>
      </Box>
      <Box
        borderWidth={1}
        borderColor={'gray.500'}
        borderRadius="lg"
        p={2}
        px={4}
      >
        <Text color="gray.400" fontSize="sm" fontWeight="bold" my={1} mb={2}>
          Botão de pagamento
        </Text>
        <VStack align="stretch" spacing={2}>
          <Box>
            <ColorPicker
              title="Cor do texto botão de pagar"
              value={template.btn_payment_text_color || '#ffffff'}
          onChange={(value) => setTemplate({ ...template, btn_payment_text_color: value })}
            />
          </Box>
          <Box>
            <ColorPicker
              title="Cor do botão de pagar"
              value={template.btn_payment_bg_color || '#0b6856'}
          onChange={(value) => setTemplate({ ...template, btn_payment_bg_color: value })}
            />
          </Box>
        </VStack>
      </Box>
    </VStack>
  )

    const isOpen = useBreakpointValue({ base: true, lg: false })

    if (!isOpen) return null;

    return (
        <Box
            position="fixed"
            bottom="0"
            left="0"
            right="0"
            height={`${drawerHeight}px`}
            maxHeight={`${drawerHeight}px`}
            bg={config.bg_color}
            zIndex={1000}
            boxShadow="0 -4px 12px rgba(0, 0, 0, 0.15)"
            borderTopRadius="lg"
        >
            {/* Resize Handle */}
            <Box
                ref={resizeRef}
                data-resize-handle
                position="absolute"
                top="-10px"
                left="50%"
                transform="translateX(-50%)"
                width="80px"
                height="20px"
                bg="gray.600"
                borderRadius="8px 8px 0 0"
                cursor="ns-resize"
                display="flex"
                alignItems="center"
                justifyContent="center"
                zIndex={1001}
                onMouseDown={handleResizeStart}
                onTouchStart={handleTouchStart}
                _hover={{ bg: 'gray.500' }}
                transition="background-color 0.2s"
                userSelect="none"
                style={{ touchAction: 'none' }}
            >
                <GripHorizontal size={16} color="white" />
            </Box>
            
            <Box
                bg={config.bg_color}
                pt={8}
                overflowY="auto"
                maxHeight={`${drawerHeight - 20}px`}
                height="100%"
            >
                <Box as="aside" bg={config.bg_color} p={6} color="white">
                    <VStack spacing={8} align="stretch">
                        <HStack spacing={4}>
                            <Button
                                fontSize={'14px'}
                                colorScheme='teal'
                                variant={activeSection === 'components' ? 'solid' : 'ghost'}
                                color={activeSection === 'components' ? 'white' : 'gray.500'}
                                _hover={{ bg: 'gray.700' }}
                                isActive={activeSection === 'components'}
                                onClick={() => setActiveSection('components')}
                            >
                                Componentes
                            </Button>
                            <Button
                                fontSize={'14px'}
                                colorScheme='teal'
                                variant={activeSection === 'layouts' ? 'solid' : 'ghost'}
                                color={activeSection === 'layouts' ? 'white' : 'gray.500'}
                                _hover={{ bg: 'gray.700' }}
                                onClick={() => setActiveSection('layouts')}
                            >
                                Linhas
                            </Button>
                            <Button
                                fontSize={'14px'}
                                colorScheme='green'
                                variant={activeSection === 'settings' ? 'solid' : 'ghost'}
                                color={activeSection === 'settings' ? 'white' : 'gray.500'}
                                _hover={{ bg: 'gray.700' }}
                                onClick={() => setActiveSection('settings')}
                            >
                                Configurações
                            </Button>
                        </HStack>
                        {activeSection === 'components' && <ComponentsSection />}
                        {activeSection === 'layouts' && <LayoutsSection />}
                        {activeSection === 'settings' && <SettingsSection />}
                    </VStack>
                </Box>
            </Box>
        </Box>
    )
}