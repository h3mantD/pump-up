import {
  CssBaseline,
  StyledEngineProvider,
  ThemeProvider,
} from "@mui/material";
import { BrowserRouter } from "react-router-dom";
import theme from "@/helpers/theme";
import { QueryClientProvider } from "@tanstack/react-query";
import queryClient from "@/helpers/queryClient";
import { ReactQueryDevtools } from "@tanstack/react-query-devtools";
import { Suspense } from "react";
import Loading from "@/components/Loading/Loading.jsx";
import { ErrorBoundary } from "react-error-boundary";
import Error from "@/components/Error/Error";

function AppProviders({ children }) {
  return (
    <ErrorBoundary fallback={<Error />}>
      <Suspense fallback={<Loading />}>
        <StyledEngineProvider injectFirst>
          <CssBaseline>
            <ThemeProvider theme={theme}>
              <BrowserRouter>
                <QueryClientProvider client={queryClient}>
                  {children}
                  <ReactQueryDevtools initialIsOpen={false} />
                </QueryClientProvider>
              </BrowserRouter>
            </ThemeProvider>
          </CssBaseline>
        </StyledEngineProvider>
      </Suspense>
    </ErrorBoundary>
  );
}

export default AppProviders;
