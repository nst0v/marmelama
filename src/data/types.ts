export type KittenStatus = 'available' | 'reserved' | 'sold';
export type Gender = 'male' | 'female';
export type ParentType = 'male' | 'female';

export interface Kitten {
  id: string;
  name: string;
  slug: string;
  gender?: Gender;
  color?: string;
  litterId?: string;
  birthDate?: string;
  status: KittenStatus;
  images: string[];
  description?: string;
}

export interface Litter {
  id: string;
  title: string;
  letter: string;
  date: string;
  boys?: number;
  girls?: number;
  status: 'active' | 'archive';
  parentIds?: string[];
  kittenIds?: string[];
}

export interface Parent {
  id: string;
  type: ParentType;
  name: string;
  color?: string;
  title?: string;
  birthday?: string;
  father?: string;
  mother?: string;
  breeder?: string;
  owner?: string;
  awards?: string;
  tests?: string;
  images: string[];
  description?: string;
  former?: boolean;
}

export interface Review {
  id: string;
  author: string;
  date: string;
  text: string;
}
