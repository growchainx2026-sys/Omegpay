import { Drawer, DrawerBody, DrawerHeader, DrawerContent, VStack, Grid, Center } from '@chakra-ui/react'
import { OptionsDrawer } from '../ui/OptionsDrawer'

type LinhaDrawerProps = {
  isOpen: boolean
  onClose: () => void
  editingRowId: any
  updateRowLayout: (id: any, layout: string) => void
  onDelete: () => void
  onDuplicate: () => void
}

export function LinhaDrawer({
  isOpen,
  onClose,
  editingRowId,
  updateRowLayout,
  onDelete,
  onDuplicate
}: LinhaDrawerProps) {

  return (
    <Drawer isOpen={isOpen} placement="right" onClose={onClose} size="sm">

      <DrawerContent bg="gray.800" maxW={'320px'}>
        <DrawerHeader borderBottomWidth="1px" borderColor="gray.700" mt={3}>
          <OptionsDrawer title="Vantagem" onDelete={onDelete} onDuplicate={onDuplicate} onClose={onClose} />
        </DrawerHeader>

        <DrawerBody>
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
                    onClick={() => updateRowLayout(editingRowId!, '1fr')}
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
                    onClick={() => updateRowLayout(editingRowId!, '9fr 3fr')}
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
                    onClick={() => updateRowLayout(editingRowId!, '1fr 2fr')}
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
                    onClick={() => updateRowLayout(editingRowId!, 'repeat(3, 1fr)')}
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
        </DrawerBody>
      </DrawerContent>
    </Drawer>
  )
}