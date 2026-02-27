export type Produto = {
    id: number
    type: 'unique' | 'subscription'
    name: string
    name_exibition: string
    description: string
    category: string
    image: string
    email_support: string
    garantia: string
    price: number
    status: boolean
    user_id: number
    uuid: string
    methods: string[] // ['pix', 'boleto', 'credit_card']
    bumps: OrderBump[]
    meta_ads: string | null
    utmfy: string | null
    google_ads: string | null
    thankyou_page: string | null
    created_at: Date
    updated_at: Date
    desconto_id?: number
    desconto_type?: 'percent' | 'fixed' 
    desconto?: number
    desconto_bumps?: boolean

}