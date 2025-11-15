# # from fastapi import FastAPI
# # from fastapi.middleware.cors import CORSMiddleware
# # from pydantic import BaseModel
# # from typing import List
# # from datetime import date
# # import pandas as pd

# # FORECASTING_WINDOW_DAYS = 28
# # PROJECTION_DAYS = 7
# # MINIMUM_DATA_POINTS = 3

# # app = FastAPI(
# #     title="Hamro Pasal Forecasting Service",
# #     description="Provides sales forecasts using an Exponential Moving Average."
# # )

# # # === CORS Configuration ===
# # origins = [
# #     "http://localhost:8000",  # Laravel frontend
# #     "http://127.0.0.1:8000",  # Optional if you use 127.0.0.1
# # ]

# # app.add_middleware(
# #     CORSMiddleware,
# #     allow_origins=origins,       # Allowed origins
# #     allow_credentials=True,
# #     allow_methods=["*"],         # Allow all HTTP methods
# #     allow_headers=["*"],         # Allow all headers
# # )

# # # === Data Models ===
# # class SalesRecord(BaseModel):
# #     date: date
# #     quantity: float

# # class SalesData(BaseModel):
# #     sales: List[SalesRecord]

# #     @classmethod
# #     def __get_validators__(cls):
# #         yield from super().__get_validators__()
# #         yield cls.validate_sales

# #     @staticmethod
# #     def validate_sales(values):
# #         if len(values['sales']) < 1:
# #             raise ValueError('At least one sales record is required.')
# #         return values

# # # === Forecast Endpoint ===
# # @app.post("/forecast")
# # def forecast(data: SalesData):
# #     df = pd.DataFrame([record.dict() for record in data.sales])
# #     df['date'] = pd.to_datetime(df['date'])
# #     df.set_index('date', inplace=True)
# #     df.sort_index(inplace=True)

# #     if len(df) < MINIMUM_DATA_POINTS:
# #         daily_avg = df['quantity'].mean()
# #     else:
# #         relevant_data = df['quantity'].tail(FORECASTING_WINDOW_DAYS)  # safer than .last()
# #         if relevant_data.empty:
# #             return {"Forecast": 0.0, "message": "No sales data in the last 28 days."}
# #         daily_avg = relevant_data.ewm(span=FORECASTING_WINDOW_DAYS, adjust=False).mean().iloc[-1]

# #     weekly_forecast = daily_avg * PROJECTION_DAYS
# #     return {"Forecast": round(weekly_forecast, 2)}


from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware
from pydantic import BaseModel
from typing import List
from datetime import date
import pandas as pd

# === Constants ===
FORECASTING_WINDOW_DAYS = 28
PROJECTION_DAYS = 7
MINIMUM_DATA_POINTS = 3

# === App Setup ===
app = FastAPI(
    title="Hamro Pasal Forecasting Service",
    description="Provides sales and revenue forecasts using an Exponential Moving Average."
)

# === CORS Configuration ===
origins = [
    "http://localhost:8000",  # Laravel frontend
    "http://127.0.0.1:8000",
]

app.add_middleware(
    CORSMiddleware,
    allow_origins=origins,
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# === Data Models ===
class SalesRecord(BaseModel):
    date: date
    quantity: float
    price: float  # <-- Added price field for revenue calculation

class SalesData(BaseModel):
    sales: List[SalesRecord]

    @classmethod
    def __get_validators__(cls):
        yield from super().__get_validators__()
        yield cls.validate_sales

    @staticmethod
    def validate_sales(values):
        if len(values['sales']) < 1:
            raise ValueError('At least one sales record is required.')
        return values

# === Forecast Endpoint ===
@app.post("/forecast")
def forecast(data: SalesData):
    # Convert input data into a DataFrame
    df = pd.DataFrame([record.dict() for record in data.sales])
    df['date'] = pd.to_datetime(df['date'])
    df.set_index('date', inplace=True)
    df.sort_index(inplace=True)

    # Add revenue column (quantity × price)
    df['revenue'] = df['quantity'] * df['price']

    # Handle insufficient data
    if len(df) < MINIMUM_DATA_POINTS:
        daily_qty_avg = df['quantity'].mean()
        daily_rev_avg = df['revenue'].mean()
    else:
        relevant_qty = df['quantity'].tail(FORECASTING_WINDOW_DAYS)
        relevant_rev = df['revenue'].tail(FORECASTING_WINDOW_DAYS)

        if relevant_qty.empty or relevant_rev.empty:
            return {"Forecast": 0.0, "message": "No recent sales data found."}

        # Exponential Moving Average (EMA)
        daily_qty_avg = relevant_qty.ewm(span=FORECASTING_WINDOW_DAYS, adjust=False).mean().iloc[-1]
        daily_rev_avg = relevant_rev.ewm(span=FORECASTING_WINDOW_DAYS, adjust=False).mean().iloc[-1]

    # Weekly projections (7 days)
    weekly_sales_forecast = daily_qty_avg * PROJECTION_DAYS
    weekly_revenue_forecast = daily_rev_avg * PROJECTION_DAYS

    return {
        "ForecastedSales": round(weekly_sales_forecast, 2),
        "ForecastedRevenue": round(weekly_revenue_forecast, 2),
        "Message": "Weekly forecast based on recent trends."
    }


# from fastapi import FastAPI
# from fastapi.middleware.cors import CORSMiddleware
# from pydantic import BaseModel
# from typing import List
# from datetime import date, timedelta
# import pandas as pd

# # === Constants ===
# FORECASTING_WINDOW_DAYS = 28
# PROJECTION_DAYS = 7
# MINIMUM_DATA_POINTS = 2

# # === App Setup ===
# app = FastAPI(
#     title="Hamro Pasal Forecasting Service",
#     description="Provides daily and weekly sales & revenue forecasts using Exponential Moving Average."
# )

# # === CORS Configuration ===
# origins = [
#     "http://localhost:8000",
#     "http://127.0.0.1:8000",
# ]

# app.add_middleware(
#     CORSMiddleware,
#     allow_origins=origins,
#     allow_credentials=True,
#     allow_methods=["*"],
#     allow_headers=["*"],
# )

# # === Data Models ===
# class SalesRecord(BaseModel):
#     date: date
#     quantity: float
#     price: float

# class SalesData(BaseModel):
#     sales: List[SalesRecord]


# # === Forecast Endpoint (Daily + Weekly) ===
# @app.post("/forecast")
# def forecast(data: SalesData):
#     # Convert input data to DataFrame
#     df = pd.DataFrame([record.dict() for record in data.sales])
#     df['date'] = pd.to_datetime(df['date'])
#     df.set_index('date', inplace=True)
#     df.sort_index(inplace=True)

#     # Add revenue column
#     df['revenue'] = df['quantity'] * df['price']

#     # Handle insufficient data
#     if len(df) < MINIMUM_DATA_POINTS:
#         daily_qty_avg = df['quantity'].mean()
#         daily_rev_avg = df['revenue'].mean()
#     else:
#         relevant_qty = df['quantity'].tail(FORECASTING_WINDOW_DAYS)
#         relevant_rev = df['revenue'].tail(FORECASTING_WINDOW_DAYS)

#         if relevant_qty.empty or relevant_rev.empty:
#             return {"Forecast": [], "message": "No recent sales data found."}

#         # Exponential Moving Averages
#         qty_ema = relevant_qty.ewm(span=FORECASTING_WINDOW_DAYS, adjust=False).mean()
#         rev_ema = relevant_rev.ewm(span=FORECASTING_WINDOW_DAYS, adjust=False).mean()

#         daily_qty_avg = qty_ema.iloc[-1]
#         daily_rev_avg = rev_ema.iloc[-1]

#     # Estimate simple growth trend (based on last two EMA points)
#     if len(df) > 1:
#         try:
#             qty_growth = (qty_ema.iloc[-1] - qty_ema.iloc[-2]) / qty_ema.iloc[-2]
#             rev_growth = (rev_ema.iloc[-1] - rev_ema.iloc[-2]) / rev_ema.iloc[-2]
#         except Exception:
#             qty_growth = 0
#             rev_growth = 0
#     else:
#         qty_growth = rev_growth = 0

#     # === Generate 7-day daily forecast ===
#     forecast_start = df.index.max() + timedelta(days=1)
#     daily_forecasts = []
#     qty_today = daily_qty_avg
#     rev_today = daily_rev_avg

#     for i in range(PROJECTION_DAYS):
#         forecast_date = forecast_start + timedelta(days=i)
#         daily_forecasts.append({
#             "date": forecast_date.date().isoformat(),
#             "predicted_sales": round(qty_today, 2),
#             "predicted_revenue": round(rev_today, 2)
#         })
#         # Apply slight growth trend per day
#         qty_today *= (1 + qty_growth)
#         rev_today *= (1 + rev_growth)

#     # === Weekly Totals ===
#     weekly_sales = sum(f["predicted_sales"] for f in daily_forecasts)
#     weekly_revenue = sum(f["predicted_revenue"] for f in daily_forecasts)

#     return {
#         "DailyForecasts": daily_forecasts,
#         "WeeklySummary": {
#             "total_sales": round(weekly_sales, 2),
#             "total_revenue": round(weekly_revenue, 2)
#         },
#         "Message": "7-day daily and total forecast based on recent trends."
#     }
