import {
  Box,
  HStack,
  Text,
  Icon,
  VStack,
  FormControl,
  FormLabel,
  Input,
  InputGroup,
  InputLeftAddon,
  Link,
  Stack,
  Image
} from '@chakra-ui/react'
import { User } from 'lucide-react'
import { useConfig } from '../stores/config'

interface UserDataFormProps {
  formData?: {
    nome?: string;
    email?: string;
    cpf?: string;
    celular?: string;
  };
  onFormChange?: (field: string, value: string) => void;
}

export function UserDataForm({ formData, onFormChange }: UserDataFormProps) {
  const { template } = useConfig();

  const handleInputChange = (field: string, value: string) => {
    if (onFormChange) {
      onFormChange(field, value);
    }
  };

  return (
    <Box p={6} borderRadius="lg" mb={8}>
      <HStack spacing={2} mb={6} align="center">
        <Icon as={User} boxSize={5} color={template.icon_color} />
        <Text fontWeight="bold" color={template.text_primary}>Seus dados</Text>
      </HStack>

      <VStack spacing={4} align="stretch">
        <FormControl>
          <FormLabel color={template.text_secondary}>Nome completo</FormLabel>
          <Input 
            color={template.text_primary} 
            value={formData?.nome || 'Nome do comprador'} 
            placeholder="Nome do comprador"
            onChange={(e) => handleInputChange('nome', e.target.value)}
          />
        </FormControl>

        <FormControl>
          <FormLabel color={template.text_secondary}>Email</FormLabel>
          <Input 
            color={template.text_primary} 
            value={formData?.email || 'email@email.com'} 
            type="email" 
            placeholder="email@email.com"
            onChange={(e) => handleInputChange('email', e.target.value)}
          />
        </FormControl>

        {/* CPF e Celular lado a lado ou empilhados */}
        <Stack direction={{ base: "column", md: "row" }} spacing={4}>
          <FormControl>
            <FormLabel color={template.text_secondary}>CPF</FormLabel>
            <Input 
              color={template.text_primary} 
              value={formData?.cpf || '304.761.160-23'} 
              placeholder="304.761.160-23"
              onChange={(e) => handleInputChange('cpf', e.target.value)}
            />
          </FormControl>

          <FormControl>
            <FormLabel color={template.text_secondary}>Celular</FormLabel>
            <InputGroup>
              <InputLeftAddon children={<Image src="/brazil-flag.png" w={5} objectFit={'cover'} />} />
              <Input 
                color={template.text_primary} 
                value={formData?.celular || '+55 (99) 99999-9999'} 
                placeholder="+55 (99) 99999-9999"
                onChange={(e) => handleInputChange('celular', e.target.value)}
              />
            </InputGroup>
          </FormControl>
        </Stack>

        <Link fontSize="sm" href="#">
          Por que pedimos esses dados?
        </Link>
      </VStack>
    </Box>
  );
}