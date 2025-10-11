<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Leaflet CSS Fix untuk marker shadow -->
    <style>
        .leaflet-marker-shadow {
            display: none !important;
        }

        .leaflet-marker-icon {
            margin-left: -12px !important;
            margin-top: -41px !important;
        }

        .leaflet-div-icon {
            background: transparent !important;
            border: none !important;
        }

        /* Custom marker styling */
        .leaflet-marker-icon.custom-div-icon {
            background-color: #3b82f6 !important;
            width: 12px !important;
            height: 12px !important;
            border-radius: 50% !important;
            border: 2px solid white !important;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.3) !important;
        }

        /* Hide 404 errors untuk marker shadow */
        .leaflet-marker-shadow img[src*="marker-shadow.png"] {
            display: none !important;
        }
    </style>

    <!-- Leaflet JavaScript Fix -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fix Leaflet marker icon path issue
            if (typeof L !== 'undefined') {
                delete L.Icon.Default.prototype._getIconUrl;

                L.Icon.Default.mergeOptions({
                    iconRetinaUrl: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAAaCAYAAABCfffaAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAAAdgAAAHYBTnsmCAAAABl0RVh0U29mdHdhcmUAd3d3Lmlua3NjYXBlLm9yZ5vuPBoAAANySURBVEiJtZZPbBNlGMafr+3XdltaLFJLQZBqYmoTjDEeTAyaGOPJgyeeOXjx4NWEgwcPJl48eDDhYOKBgxcPHjhw8GDixYMHDx48ePDiwYsHE2MSoyQmJiQkJCQkxr/hM33fN37ftq1tb7/k+96+7/u8z+/53vedb9q2rbLZrJLJpDo6OtTV1aX29na1tbWptbVVLS0tqq+vV21trWpqalRdXa2qqipVVlaqoqJC5eXlKisrU2lpqUpKSlRcXKyioiIVFhaqoKBA+fn5ysvLU25urnJycpSdna2srCxlZmYqIyND6enpSktLU2pqqlJSUpScnKykpCQlJiYqISFB8fHxiouLU2xsrGJiYhQdHa2oqChFRkYqIiJC4eHhCgsLU2hoqEJCQhQcHKygoCDl5eXF5Ofn/1ReXr4uMjLyz7a2tpfWrVt3zM/Pzx8dO3bs4MCBAz0NDQ3Lz58/f6K2tpa1tbU/Ozs7Ozg6OjooJyfnT39/f7a1tXXV8fHxH5ycnJydnZ1/cXFx+YWFhX9ZWVl+fn7+v9jYWH9MTIwXFRXlBQYGepcvX/YuXLjgXbhwwbt48aJ34cIF7/z5896ZM2e8U6dOeceOHfOOHDniHTp0yDt48KC3f/9+b+/evd6ePXu83bt3e7t27fJ27NjhdXd3e1u3bvW2bNnibd682du0aZO3ceNGb8OGDV59fb23bt06r66uzqupqfFqa2u91atXe6tWrfJWrlzprVixwqusrPSWL1/uLVu2zFu6dKm3ZMkSb/HixV5VVZW3aNEib+HChd6CBQu8+fPne/PmzfPmzp3rzZkzx5s9e7Y3a9Ysb+bMmd6MGTO86dOne9OmTfOmTp3qTZkyxZs8ebI3adIkb+LEid6ECRO88ePHe+PGjfPGjh3rjRkzxhs9erQ3atQob+TIkd6IESO84cOHe8OGDfOGDh3qDRkyxBs8eLA3aNAgb+DAgd6AAQO8/v37e/369fP69u3r9enTx+vdu7fXq1cvr2fPnl6PHj287t27e926dfO6du3qdenSxevcubPXqVMnr2PHjl6HDh289u3be+3atfPatm3rtWnTxmvdurXXqlUrr2XLll6LFi285s2be82aNfOaNm3qNWnSxGvcuLHXqFEjr2HDhl7Dhg29Bg0aeA0aNPDq16/v1a9f36tbt65Xp04dr3bt2l7t2rW9WrVqeTVr1vRq1Kjh1ahRw6tWrZpXtWpVr0qVKl7lypW9SpUqeRUrVvQqVKjgla9f3ysvL/fKysq8srIyr7S01CstLfVKSkq8kpISr3jx4l5xcbFXVFTkFRUVeYWFhV5hYaFXUFDgFRQUePn5+V5+fr6Xl5fn5ebmerm5uV5OTo6Xk5PjZWdne9nZ2V5WVpaXlZXlZWZmepnZ2V5mZqaXkZHhZWRkeOnp6V56erqXlpbmpaWleampqV5qaqr//xf+A/EjUcf8zCHyAAAAAElFTkSuQmCC',
                    iconUrl: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAAaCAYAAABCfffaAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAAAdgAAAHYBTnsmCAAAABl0RVh0U29mdHdhcmUAd3d3Lmlua3NjYXBlLm9yZ5vuPBoAAANySURBVEiJtZZPbBNlGMafr+3XdltaLFJLQZBqYmoTjDEeTAyaGOPJgyeeOXjx4NWEgwcPJl48eDDhYOKBgxcPHjhw8GDixYMHDx48ePDiwYsHE2MSoyQmJiQkJCQkxr/hM33fN37ftq1tb7/k+96+7/u8z+/53vedb9q2rbLZrJLJpDo6OtTV1aX29na1tbWptbVVLS0tqq+vV21trWpqalRdXa2qqipVVlaqoqJC5eXlKisrU2lpqUpKSlRcXKyioiIVFhaqoKBA+fn5ysvLU25urnJycpSdna2srCxlZmYqIyND6enpSktLU2pqqlJSUpScnKykpCQlJiYqISFB8fHxiouLU2xsrGJiYhQdHa2oqChFRkYqIiJC4eHhCgsLU2hoqEJCQhQcHKygoCDl5eXF5Ofn/1ReXr4uMjLyz7a2tpfWrVt3zM/Pzx8dO3bs4MCBAz0NDQ3Lz58/f6K2tpa1tbU/Ozs7Ozg6OjooJyfnT39/f7a1tXXV8fHxH5ycnJydnZ1/cXFx+YWFhX9ZWVl+fn7+v9jYWH9MTIwXFRXlBQYGepcvX/YuXLjgXbhwwbt48aJ34cIF7/z5896ZM2e8U6dOeceOHfOOHDniHTp0yDt48KC3f/9+b+/evd6ePXu83bt3e7t27fJ27NjhdXd3e1u3bvW2bNnibd682du0aZO3ceNGb8OGDV59fb23bt06r66uzqupqfFqa2u91atXe6tWrfJWrlzprVixwqusrPSWL1/uLVu2zFu6dKm3ZMkSb/HixV5VVZW3aNEib+HChd6CBQu8+fPne/PmzfPmzp3rzZkzx5s9e7Y3a9Ysb+bMmd6MGTO86dOne9OmTfOmTp3qTZkyxZs8ebI3adIkb+LEid6ECRO88ePHe+PGjfPGjh3rjRkzxhs9erQ3atQob+TIkd6IESO84cOHe8OGDfOGDh3qDRkyxBs8eLA3aNAgb+DAgd6AAQO8/v37e/369fP69u3r9enTx+vdu7fXq1cvr2fPnl6PHj287t27e926dfO6du3qdenSxevcubPXqVMnr2PHjl6HDh289u3be+3atfPatm3rtWnTxmvdurXXqlUrr2XLll6LFi285s2be82aNfOaNm3qNWnSxGvcuLHXqFEjr2HDhl7Dhg29Bg0aeA0aNPDq16/v1a9f36tbt65Xp04dr3bt2l7t2rW9WrVqeTVr1vRq1Kjh1ahRw6tWrZpXtWpVr0qVKl7lypW9SpUqeRUrVvQqVKjgla9f3ysvL/fKysq8srIyr7S01CstLfVKSkq8kpISr3jx4l5xcbFXVFTkFRUVeYWFhV5hYaFXUFDgFRQUePn5+V5+fr6Xl5fn5ebmerm5uV5OTo6Xk5PjZWdne9nZ2V5WVpaXlZXlZWZmepnZ2V5mZqaXkZHhZWRkeOnp6V56erqXlpbmpaWleampqV5qaqr//xf+A/EjUcf8zCHyAAAAAElFTkSuQmCC',
                    shadowUrl: '',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowSize: [0, 0]
                });
            }

            // Suppress marker shadow 404 errors
            var originalConsoleError = console.error;
            console.error = function(message) {
                if (typeof message === 'string' && message.includes('marker-shadow.png')) {
                    return;
                }
                originalConsoleError.apply(console, arguments);
            };
        });
    </script>
</head>

<body>
    {{ $slot }}
</body>

</html>
