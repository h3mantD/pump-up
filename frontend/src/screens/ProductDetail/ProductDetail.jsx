import Loading from "@/components/Loading/Loading";
import ChatBot from "@/containers/ChatBot/ChatBot";
import { useProduct } from "@/services/products.service";
import { Box, Container, Grid, Typography } from "@mui/material";
import { useParams } from "react-router-dom";

function ProductDetail() {
  const { id } = useParams();
  const product = useProduct(id);
  if (product.isLoading) {
    return <Loading />;
  }

  if (product.isError) {
    return <p>Error</p>;
  }

  return (
    <Container maxWidth="xl" sx={{ py: 2, height: "100vh" }}>
      <Typography variant="h4">{product.data.name}</Typography>
      <Grid container spacing={3}>
        <Grid item xs={12} sm={6}>
          <Box
            component="img"
            sx={{ height: 400, width: 400, objectFit: "contain" }}
            src={`https://picsum.photos/id/${product.data.id}/250/250`}
          />
        </Grid>
        <Grid item xs={12} sm={6}>
          <Typography variant="h6">{product.data.description}</Typography>
          <Typography variant="h6" color="text.secondary">
            Price: ${product.data.price}
          </Typography>
          {product.data.stock > 0 ? (
            <Typography variant="h6" color="text.secondary">
              In Stock
            </Typography>
          ) : (
            <Typography variant="h6" color="text.secondary">
              Out of Stock
            </Typography>
          )}
        </Grid>
      </Grid>
    </Container>
  );
}

export default ProductDetail;
