import { icons } from "../components/drawers/VantagemDrawer";
import type { VantagemComponentProps } from "../types/components";

export const vantagemData: VantagemComponentProps = {
        id: Date.now().toString(),
        type: 'vantagem',
        icon: icons[10].label,
        title: 'Garantia de 7 dias',
        subtitle: 'Experimente sem riscos',
        title_color: '#000000',
        subtitle_color: '#0F7865',
        mode: 'horizontal',
        background: 'transparent'
      }