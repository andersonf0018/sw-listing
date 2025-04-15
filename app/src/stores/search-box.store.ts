import { create } from "zustand";
import { SearchCategory } from "@/types";

interface SearchBoxStore {
  search: string;
  setSearch: (search: string) => void;
  selectedOption: SearchCategory | null;
  setSelectedOption: (option: SearchCategory) => void;
}

export const useSearchBoxStore = create<SearchBoxStore>((set) => ({
  search: "",
  setSearch: (search) => set({ search }),
  selectedOption: null,
  setSelectedOption: (option) => set({ selectedOption: option }),
}));