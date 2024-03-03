import { useQuery } from "@tanstack/react-query";
import api from "@/helpers/api";
import endpoints from "@/helpers/endpoints";

export const useProducts = () => {
  return useQuery({
    queryKey: ["products"],
    queryFn: () => {
      return api({
        method: "get",
        url: endpoints.products
      });
    },
    select: (data) => data.data.data
  });
};

export const useProduct = (id) => {
  return useQuery({
    queryKey: ["product", id],
    queryFn: () => {
      return api({
        method: "get",
        url: endpoints.product(id)
      });
    },
    enabled: !!id,
    select: (data) => data.data.product
  });
};
