import {
  VStack,
  FormControl,
  FormLabel,
  Input,
  Heading,
  FormErrorMessage,
  HStack,
  Icon,
} from '@chakra-ui/react'
import { Field } from 'formik'
import { useMask } from '@react-input/mask'
import { User } from 'lucide-react'
import useConfig from '../stores/config'
import { useRef } from 'react'

export function BuyerForm() {
  const { template, checkout } = useConfig()

  // Criação das refs de máscara
  const cpfRef = useMask({ mask: '###.###.###-##', replacement: { "#": /\d/ } })
  const phoneRef = useMask({ mask: '(##) #####-####', replacement: { "#": /\d/ } })

  return (
    <VStack spacing={4} p={2} align="stretch">
      <HStack gap={2}>
        <Icon as={User} boxSize={5} color={checkout?.template.icon_color} />
        <Heading size="md">Seus dados</Heading>
      </HStack>

      <Field name="buyer.name">
        {({ field, meta }: any) => (
          <FormControl isInvalid={meta.error && meta.touched}>
            <FormLabel>Nome completo</FormLabel>
            <Input {...field} placeholder="Nome do comprador" />
            <FormErrorMessage>{meta.error}</FormErrorMessage>
          </FormControl>
        )}
      </Field>

      <Field name="buyer.email">
        {({ field, meta }: any) => (
          <FormControl isInvalid={meta.error && meta.touched}>
            <FormLabel>Email</FormLabel>
            <Input {...field} type="email" placeholder="email@email.com" />
            <FormErrorMessage>{meta.error}</FormErrorMessage>
          </FormControl>
        )}
      </Field>

      <Field name="buyer.cpf">
        {({ field, meta }: any) => (
          <FormControl isInvalid={meta.error && meta.touched}>
            <FormLabel>CPF</FormLabel>
            <Input
              {...field}
              ref={cpfRef}
              placeholder="000.000.000-00"
            />
            <FormErrorMessage>{meta.error}</FormErrorMessage>
          </FormControl>
        )}
      </Field>

      <Field name="buyer.phone">
        {({ field, meta }: any) => (
          <FormControl isInvalid={meta.error && meta.touched}>
            <FormLabel>Celular</FormLabel>
            <Input
              {...field}
              ref={phoneRef}
              placeholder="(99) 99999-9999"
            />
            <FormErrorMessage>{meta.error}</FormErrorMessage>
          </FormControl>
        )}
      </Field>
    </VStack>
  )
}
