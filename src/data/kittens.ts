import type { Kitten } from './types';

export const kittens: Kitten[] = [
  {
    id: '126',
    slug: '126',
    name: 'Котенок',
    gender: 'male',
    color: 'соболиный',
    litterId: '34',
    birthDate: '06.03.2026',
    status: 'available',
    images: ['/images/kittens/12474620260507_164747.jpg'],
    description: 'Мальчик соболиного окраса.'
  },
  {
    id: '125',
    slug: '125',
    name: 'Nicon',
    gender: 'male',
    color: 'шоколадный',
    litterId: '34',
    birthDate: '06.03.2026',
    status: 'available',
    images: ['/images/kittens/123603Screenshot_20260513_143617_Gallery.jpg'],
    description: 'Мальчик шоколадного окраса.'
  },
  {
    id: '124',
    slug: '124',
    name: 'Nicholas',
    gender: 'male',
    color: 'шоколадный',
    litterId: '34',
    birthDate: '06.03.2026',
    status: 'available',
    images: [
      '/images/kittens/163739avito-1778218431181-shot.jpg',
      '/images/kittens/16374020260507_163228.jpg'
    ],
    description: 'Мальчик шоколадного окраса.'
  },
  {
    id: '123',
    slug: '123',
    name: 'Nala',
    gender: 'female',
    color: 'соболиный',
    litterId: '34',
    birthDate: '06.03.2026',
    status: 'available',
    images: [
      '/images/kittens/16361720260506_172348.jpg',
      '/images/kittens/16361720260506_172328.jpg'
    ],
    description: 'Девочка соболиного окраса.'
  },
  {
    id: '122',
    slug: '122',
    name: 'Nord Stream',
    gender: 'male',
    color: 'соболиный',
    litterId: '34',
    birthDate: '06.03.2026',
    status: 'available',
    images: [
      '/images/kittens/16330620260506_165919.jpg',
      '/images/kittens/16330620260506_165529.jpg'
    ],
    description: 'Мальчик соболиного окраса.'
  },
  {
    id: '121',
    slug: '121',
    name: 'Melody',
    gender: 'female',
    color: 'шоколадный',
    litterId: '33',
    birthDate: '01.02.2026',
    status: 'available',
    images: [
      '/images/kittens/1639361776314718380.jpg',
      '/images/kittens/09445820260316_110306.jpg',
      '/images/kittens/09445820260316_110353.jpg'
    ],
    description: 'Девочка из помета M.'
  }
];

export const getKittenById = (id: string) => kittens.find((kitten) => kitten.id === id);
export const availableKittens = kittens.filter((kitten) => kitten.status === 'available');
