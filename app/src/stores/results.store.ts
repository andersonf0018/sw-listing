import { ApiResponse, Film, Person } from "@/types/api";
import { create } from "zustand";

interface ResultsStore {
  totalResults: number;
  setTotalResults: (total: number) => void;
  results: ApiResponse<Person | Film>;
  setResults: (results: ApiResponse<Person | Film>) => void;
  currentPage: number;
  setCurrentPage: (page: number) => void;
}

export const useResultsStore = create<ResultsStore>((set) => ({
  totalResults: 0,
  setTotalResults: (total) => set({ totalResults: total }),
  results: {
    count: 0,
    results: [],
  },
  setResults: (results) => set({ results }),
  currentPage: 1,
  setCurrentPage: (page) => set({ currentPage: page }),
}));