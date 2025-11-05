from fastapi import FastAPI
from pydantic import BaseModel, conlist
from typing import List
from datetime import date
import pandas as pd

FORECASTING_WINDOW_DAYS = 28
PROJECTION_DAYS = 7
MINIMUM_DATA_POINTS = 7

app = FastAPI(
    title="Bazaar Buddy Forecasting Service",
    description="Provides sales forecasts using an Exponential Moving Average."
)

class SalesRecord(BaseModel):
    date: date
    quantity: float

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

@app.post("/forecast")
def forecast(data: SalesData):
    df = pd.DataFrame([record.dict() for record in data.sales])
    df['date'] = pd.to_datetime(df['date'])
    df.set_index('date', inplace=True)
    df.sort_index(inplace=True)

    if len(df) < MINIMUM_DATA_POINTS:
        daily_avg = df['quantity'].mean()
    else:
        relevant_data = df['quantity'].last(f'{FORECASTING_WINDOW_DAYS}D')
        if relevant_data.empty:
            return {"forecast": 0.0, "message": "No sales data in the last 28 days."}
        daily_avg = relevant_data.ewm(span=FORECASTING_WINDOW_DAYS, adjust=False).mean().iloc[-1]

    weekly_forecast = daily_avg * PROJECTION_DAYS
    return {"forecast": round(weekly_forecast, 2)}