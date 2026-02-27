import { Box, Modal, ModalOverlay, ModalContent, ModalCloseButton } from '@chakra-ui/react'
import { ReactNode } from 'react'

interface MacBookFrameProps {
  isOpen: boolean
  onClose: () => void
  children: ReactNode
}

export function MacBookFrame({ isOpen, onClose, children }: MacBookFrameProps) {
  return (
    <Modal isOpen={isOpen} onClose={onClose} size="6xl" isCentered>
      <ModalOverlay bg="blackAlpha.800" />
      <ModalContent
        bg="transparent"
        boxShadow="none"
        maxW="90vw"
        maxH="90vh"
        p={0}
      >
        <ModalCloseButton
          color="white"
          bg="blackAlpha.600"
          _hover={{ bg: "blackAlpha.800" }}
          zIndex={1001}
          top={4}
          right={4}
        />
        
        {/* MacBook Frame */}
        <Box
          position="relative"
          w="100%"
          h="100%"
          display="flex"
          alignItems="center"
          justifyContent="center"
        >
          {/* MacBook Screen */}
          <Box
            position="relative"
            w="90vw"
            h="90vh"
            bg="#1a1a1a"
            borderRadius="20px"
            border="12px solid #2d2d2d"
            borderBottomRadius={0}
            boxShadow="0 0 50px rgba(0,0,0,0.8)"
            overflow="hidden"
          >
            {/* Screen Content */}
            <Box
              w="100%"
              h="100%"
              bg="white"
              borderRadius="12px"
              overflow="auto"
              position="relative"
            >
              {children}
            </Box>
            
            {/* Camera */}
            <Box
              position="absolute"
              top="-4px"
              left="50%"
              transform="translateX(-50%)"
              w="10px"
              h="10px"
              bg="#333"
              borderRadius="50%"
              zIndex={10}
            />
          </Box>
          
          {/* MacBook Base */}
          <Box
            position="absolute"
            bottom="-20px"
            w="90vw"
            h="20px"
            bg="linear-gradient(to bottom, #e0e0e0, #c0c0c0)"
            borderRadius="0 0 40px 40px"
            zIndex={-1}
          />
          
          {/* MacBook Hinge */}
          <Box
            position="absolute"
            bottom="-8px"
            w="50%"
            h="8px"
            bg="#b0b0b0"
            borderRadius="4px"
            zIndex={-1}
          />
        </Box>
      </ModalContent>
    </Modal>
  )
}