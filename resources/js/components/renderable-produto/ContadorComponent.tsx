import { HStack, Icon, Text } from "@chakra-ui/react"
import { useEffect, useState } from "react"
import type { ContadorComponentProps } from "../../types/components"
import { LucideAlarmClock } from "lucide-react"


export const ContadorComponent = ({ component, handleComponentClick }: { component: ContadorComponentProps, handleComponentClick: any }) => {
    const [timeLeft, setTimeLeft] = useState<string>('00:00:00')
    const [isFinished, setIsFinished] = useState<boolean>(false)
    const [endTime, setEndTime] = useState<Date | null>(null)

    useEffect(() => {
         let targetTime: Date
        
        // Se time é um timestamp (string de data)
        if (typeof component.time === 'string' && component.time.includes('T')) {
            targetTime = new Date(component.time)
            
            // Se o timestamp está no passado, criar um timestamp futuro para demonstração
            if (targetTime.getTime() <= Date.now()) {
                targetTime = new Date(Date.now() + 300000) // 5 minutos no futuro
            }
        } 
        // Se time é um número (segundos)
        else if (typeof component.time === 'string' || !isNaN(Number(component.time))) {
            let dth = component.time.split(':');
            let segundos = 0;
            if (dth.length === 3) {
                segundos = Number(dth[0]) * 60 * 60 + Number(dth[1]) * 60 + Number(dth[2]);
            } else if (dth.length === 2) {
                segundos = Number(dth[0]) * 60 * 60 + Number(dth[1])* 60;
            } else if (dth.length === 1) {
                segundos = Number(dth[0]);
            }

            const seconds = Number(segundos)
            targetTime = new Date(Date.now() + seconds * 1000)
        }
        else {
            setIsFinished(true)
            return
        }
        
        // Verificar se a data é válida
        if (isNaN(targetTime.getTime())) {
            setIsFinished(true)
            setTimeLeft('00:00:00')
            return
        }
        
        setEndTime(targetTime)
        
        // Calcular tempo inicial
        const now = new Date().getTime()
        const difference = targetTime.getTime() - now
                
        if (difference <= 0) {
            setIsFinished(true)
            setTimeLeft('00:00:00')
        } else {
            const days = Math.floor(difference / (1000 * 60 * 60 * 24))
            const hours = Math.floor((difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))
            const minutes = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60))
            const seconds = Math.floor((difference % (1000 * 60)) / 1000)

            let timeString = ''
            if (days > 0) {
                timeString = `${days}d ${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`
            } else {
                timeString = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`
            }
            
            setTimeLeft(timeString)
        }
        
    }, [component.time])
    
    useEffect(() => {
        if (!endTime) return
        
        const calculateTimeLeft = () => {
            const now = new Date().getTime()
            const difference = endTime.getTime() - now

            if (difference <= 0) {
                setIsFinished(true)
                return '00:00:00'
            }

            const days = Math.floor(difference / (1000 * 60 * 60 * 24))
            const hours = Math.floor((difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))
            const minutes = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60))
            const seconds = Math.floor((difference % (1000 * 60)) / 1000)

            if (days > 0) {
                return `${days}d ${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`
            } else {
                return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`
            }
        }

        // Configurar intervalo para atualizar a cada segundo
        const interval = setInterval(() => {
            const newTimeLeft = calculateTimeLeft()
            setTimeLeft(newTimeLeft)
        }, 1000)

        // Limpar intervalo quando componente for desmontado
        return () => clearInterval(interval)
    }, [endTime])

    return (
        <HStack
            w={'100%'}
            key={component.id}
            p={4}
            bg={component.bgColor}
            color={component.textColor}
            borderRadius="md"
            textAlign="center"
            onClick={() => handleComponentClick(component)}
            cursor="pointer"
            justifyContent={'center'}
            gap={4}
            fontSize={18}
            fontWeight={'bold'}
        >
            <Text fontSize="lg">
                {timeLeft}
            </Text>
            <Icon as={LucideAlarmClock} boxSize={8} />
            <Text fontSize="md">{isFinished ? component.textFinalizado : component.textActive}</Text>
        </HStack>
    )
}
