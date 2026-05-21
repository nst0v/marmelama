import type { Parent } from './types';

export const parents: Parent[] = [
  {
    id: '76',
    type: 'male',
    name: 'Grafian Royal Claw',
    title: 'Ch',
    color: 'шоколадный',
    birthday: '22.02.2023',
    father: 'Int.Ch. Wegas Nala Altana',
    mother: 'Ch. Tiffany Trail Lynx&Espero Mio',
    breeder: 'Левина Татьяна',
    owner: 'Иванова Елена',
    awards: 'Ch',
    tests: 'Гипокалиемия бурм NN; Ганглиозидоз бурм GM2 NN',
    images: [
      '/images/parents/144948IMG_20241031_091926_115.jpg',
      '/images/parents/023844thumb_2114581D9A0350-got.jpg',
      '/images/parents/023844thumb_2114581D9A0411-got.jpg'
    ],
    description: 'Кот шоколадного окраса, закрыл титул Чемпиона породы и открыл титул Международный чемпион. Продолжает выставочную карьеру.'
  },
  {
    id: '77',
    type: 'female',
    name: 'Zosima Zvezda DiMario',
    title: 'JCh',
    color: 'соболиный',
    birthday: '23.09.2024',
    father: "Gr.Int.Ch. Ru*O'Kler Nicolas",
    mother: 'J.Ch Olivia Zvezda DiMario',
    breeder: 'Королюк Мария',
    owner: 'Иванова Елена',
    awards: 'JCh',
    tests: 'Гипокалиемия бурм NN; Ганглиозидоз бурм GM2 NN',
    images: ['/images/parents/0654061D9A3911.jpg']
  },
  {
    id: '79',
    type: 'female',
    name: 'Iris MarMelAma',
    color: 'шоколадный',
    birthday: '07.03.2025',
    father: 'Ch Grafian Royal Claw',
    mother: 'Bailey Burm Diva',
    breeder: 'Иванова Елена',
    owner: 'Иванова Елена',
    awards: '-',
    images: ['/images/parents/084141Iris.jpg']
  },
  {
    id: '74',
    type: 'female',
    name: 'Queen Zvezda DiMario',
    title: 'JCh',
    color: 'шоколадный',
    birthday: '29.06.2023',
    father: "Gr.Int.Ch. Ru*O'Kler Nicolas",
    mother: 'Bailey Burm Katniss Everdeen',
    breeder: 'Королюк Мария',
    owner: 'Иванова Елена',
    awards: 'JCh',
    tests: 'Гипокалиемия бурм NN; Ганглиозидоз бурм GM2 NN',
    images: ['/images/parents/020958Queen.jpg']
  },
  {
    id: '75',
    type: 'female',
    name: 'Diva Bailey Burm',
    color: 'шоколадный',
    birthday: '28.04.2023',
    father: 'Ch. G',
    mother: 'Bailey Burm Harley Quinn',
    breeder: 'Корнева Алина',
    owner: 'Иванова Елена',
    awards: '-',
    former: true,
    images: ['/images/parents/06252520250121_150929.jpg']
  }
];

export const getParentById = (id: string) => parents.find((parent) => parent.id === id);
export const maleParents = parents.filter((parent) => parent.type === 'male' && !parent.former);
export const femaleParents = parents.filter((parent) => parent.type === 'female' && !parent.former);
export const formerParents = parents.filter((parent) => parent.former);
