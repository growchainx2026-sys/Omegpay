import { HStack, Button, Icon, Image, Input, IconButton, useDisclosure, useToast } from '@chakra-ui/react'
import useConfig from '../stores/config';
import { LucideEye, LucideMonitorCheck, LucideSmartphone } from 'lucide-react';
import { MacBookFrame } from './ui/MacBookFrame';
import { Main } from './Main';
import { useState } from 'react';

export function Header() {

  const toast = useToast();
  const { setting, checkout, config, setDevice, device, depoimentos } = useConfig();
  const { isOpen, onOpen, onClose } = useDisclosure();

  const [isSend, setIsSend] = useState<boolean>(false);

  const { template, layout, produto } = useConfig();

  const handleSave = async () => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    setIsSend(true);
    let response = await fetch(`/checkout-builder/${checkout?.uuid}`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken as string
      },
      body: JSON.stringify({ template, layout, depoimentos })
    });

    let res = await response.json();
    if (res.status === 'success') {
      toast({
        title: res.message,
        status: 'success',
        duration: 5000,
        isClosable: true,
      });
      setIsSend(false)
    } else {
      toast({
        title: 'Houve um erro ao salvar o checkout... Tente novamente mais tarde.',
        status: 'error',
        position: 'top',
        duration: 5000,
        isClosable: true,
      });
      setIsSend(false)
    }
  }

  return (
    <HStack
      zIndex={999}
      pos={'fixed'}
      w={'100%'}
      h={'75px'}
      bg={config.card_bg_color}
      borderBottomWidth={1}
      borderBottomColor={config.border_color}
      boxShadow={"lg"}
      px={8}
      display={'flex'}
      alignItems={'center'}
      justifyContent={'space-between'}
    >

      <Image
        src={`/storage/${setting.favicon_light}`}
        w={'40px'}
        h={'auto'}
        objectFit={'contain'}
      />

      <HStack
        gap={4}
        alignItems={'center'}
        justifyContent={'space-between'}
      >

        <IconButton
          aria-label='Desktop'
          icon={<LucideMonitorCheck />}
          variant={device === 'desktop' ? 'solid' : 'ghost'}
          colorScheme={'teal'}
          onClick={() => setDevice('desktop')}
        />
        <IconButton
          aria-label='Mobile'
          icon={<LucideSmartphone />}
          variant={device === 'mobile' ? 'solid' : 'ghost'}
          colorScheme={'teal'}
          onClick={() => setDevice('mobile')}
        />
      </HStack>
      <HStack
        gap={4}
      >
        <Button
          colorScheme="teal"
          size="md"
          onClick={handleSave}
          loadingText="Salvando..."
          isLoading={isSend}
        >Salvar</Button>
        {/* <Button colorScheme="teal" size="md" variant={'outline'} onClick={onOpen}>
          Preview&nbsp;
          <Icon as={LucideEye} />
        </Button> */}
      </HStack>

      {/* <MacBookFrame isOpen={isOpen} onClose={onClose}>
        <Main />
      </MacBookFrame> */}
    </HStack>
  );
}