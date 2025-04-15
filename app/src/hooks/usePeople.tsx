import { api } from "@/api";
import { Person } from "@/types";
import { useQuery } from "@tanstack/react-query";

export const usePeople = (id: string) => {
  return useQuery({
    queryKey: ["person", id],
    queryFn: async () => {
      const response = await api.get(`/people/${id}`);
      return response.data as Person;
    },
  });
};
