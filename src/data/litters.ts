import type { Litter } from './types';

export const litters: Litter[] = [
  {
    id: '34',
    title: 'Помет N',
    letter: 'N',
    date: '06.03.2026',
    boys: 4,
    girls: 1,
    status: 'active',
    parentIds: ['76'],
    kittenIds: ['126', '125', '124', '123', '122']
  },
  {
    id: '33',
    title: 'Помет M',
    letter: 'M',
    date: '01.02.2026',
    boys: 1,
    girls: 1,
    status: 'active',
    kittenIds: ['121']
  },
  { id: '32', title: 'Помет L', letter: 'L', date: '17.09.2025', boys: 1, girls: 3, status: 'archive' },
  { id: '31', title: 'Помет K', letter: 'K', date: '04.08.2025', boys: 2, girls: 0, status: 'archive' },
  { id: '30', title: 'Помет J', letter: 'J', date: '04.06.2025', boys: 2, girls: 1, status: 'archive' },
  { id: '29', title: 'Помет I', letter: 'I', date: '07.03.2025', boys: 3, girls: 1, status: 'archive' },
  { id: '28', title: 'Помет H', letter: 'H', date: '07.01.2025', boys: 3, girls: 1, status: 'archive' },
  { id: '27', title: 'Помет G', letter: 'G', date: '13.10.2024', boys: 3, girls: 0, status: 'archive' },
  { id: '26', title: 'Помет F', letter: 'F', date: '25.08.2024', boys: 2, girls: 0, status: 'archive' }
];

export const getLitterById = (id: string) => litters.find((litter) => litter.id === id);
