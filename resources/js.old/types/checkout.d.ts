import { Component } from "./components"
import { Template } from "./template"

export interface Checkout {
    id: number 
	name: string 
	config: string 
	created_at: Date 
	updated_at: Date 
	default: boolean 
	visits: number 
	produto_id: number 
	user_id: number 
	price: number 
	oferta: string 
	uuid: string 
    template: Template
    layout?: Component
    depoimentos?: any[]
}